<?php


namespace App\Http\Controllers\Admin;

// RGPD

// ecosystem
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Actor;
use App\Models\Annuaire;
use App\Models\ApplicationBlock;
use App\Models\ApplicationModule;
use App\Models\ApplicationService;
use App\Models\Bay;
use App\Models\Building;
use App\Models\Certificate;
use App\Models\Cluster;
use App\Models\Container;
use App\Models\Database;
use App\Models\DhcpServer;
use App\Models\Dnsserver;
use App\Models\DomaineAd;
use App\Models\Entity;
use App\Models\ExternalConnectedEntity;
use App\Models\Flux;
use App\Models\ForestAd;
use App\Models\Gateway;
use App\Models\Information;
use App\Models\Lan;
use App\Models\LogicalServer;
use App\Models\MacroProcessus;
use App\Models\Man;
use App\Models\MApplication;
use App\Models\Network;
use App\Models\NetworkSwitch;
use App\Models\Operation;
use App\Models\Peripheral;
use App\Models\Phone;
use App\Models\PhysicalRouter;
use App\Models\PhysicalSecurityDevice;
use App\Models\PhysicalServer;
use App\Models\PhysicalSwitch;
use App\Models\Process;
use App\Models\Relation;
use App\Models\Router;
use App\Models\SecurityDevice;
use App\Models\Site;
use App\Models\StorageDevice;
use App\Models\Subnetwork;
use App\Models\Task;
use App\Models\Vlan;
use App\Models\Wan;
use App\Models\WifiTerminal;
use App\Models\Workstation;
use App\Models\ZoneAdmin;
use Auth;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Shared\Html;
use Symfony\Component\HttpFoundation\Response;

class CartographyController extends Controller
{
    // Cell style
    public const FANCYTABLETITLESTYLE = ['bold' => true, 'color' => '006699'];

    public const FANCYLEFTTABLECELLSTYLE = ['bold' => true, 'color' => '000000'];

    public const FANCYRIGHTTABLECELLSTYLE = ['bold' => false, 'color' => '000000'];

    public const FANCYLINKSTYLE = ['color' => '006699'];

    public const NOSPACE = [
        'spaceBefore' => 30,
        'spaceAfter' => 30,
    ];

    public function cartography(Request $request)
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Image paths
        $image_paths = [];

        // get parameters
        $granularity = (int) $request->granularity;
        $vues = $request->input('vues', []);

        // get template
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        $phpWord->getSettings()->setHideGrammaticalErrors(true);
        $phpWord->getSettings()->setHideSpellingErrors(true);

        // Numbering Style
        $phpWord->addNumberingStyle(
            'hNum',
            ['type' => 'multilevel', 'levels' => [
                ['pStyle' => 'Heading1', 'format' => 'decimal', 'text' => '%1.'],
                ['pStyle' => 'Heading2', 'format' => 'decimal', 'text' => '%1.%2.'],
                ['pStyle' => 'Heading3', 'format' => 'decimal', 'text' => '%1.%2.%3.'],
            ],
            ]
        );
        $phpWord->addTitleStyle(
            0,
            ['size' => 28, 'bold' => true],
            ['align' => 'center']
        );
        $phpWord->addTitleStyle(
            1,
            ['size' => 16, 'bold' => true],
            ['numStyle' => 'hNum', 'numLevel' => 0]
        );
        $phpWord->addTitleStyle(
            2,
            ['size' => 14, 'bold' => true],
            ['numStyle' => 'hNum', 'numLevel' => 1]
        );
        $phpWord->addTitleStyle(
            3,
            ['size' => 12, 'bold' => true],
            ['numStyle' => 'hNum', 'numLevel' => 2]
        );

        // Title
        $section = $phpWord->addSection();
        $section->addTitle(trans('cruds.report.cartography.title'), 0);
        $section->addTextBreak(1);

        // TOC
        $toc = $section->addTOC(['spaceAfter' => 50, 'size' => 10]);
        $toc->setMinDepth(1);
        $toc->setMaxDepth(3);
        $section->addTextBreak(1);

        // page break
        $section->addPageBreak();

        // Add footer
        $footer = $section->addFooter();
        $footer->addPreserveText('{PAGE} / {NUMPAGES}', ['size' => 8], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        // ====================
        // ==== Ecosystème ====
        // ====================
        if ($vues === null || count($vues) === 0 || in_array('1', $vues)) {
            // schema
            $section->addTitle(trans('cruds.menu.ecosystem.title'), 1);
            $section->addText(trans('cruds.menu.ecosystem.description'));

            // Get data
            $entities = Entity::orderBy('name')->get();
            $relations = Relation::orderBy('name')->get();

            // Generate Graph
            if ($request->has('graph')) {
                $graph = 'digraph  {';

                // use font from the library fonts-freefont-ttf
                $graph .= 'graph [fontname = "FreeSans"];';
                $graph .= 'node [fontname = "FreeSans"];';
                $graph .= 'edge [fontname = "FreeSans"];';

                foreach ($entities as $entity) {
                    $graph .= 'E'.$entity->id.$this->dotImage('/images/entity.png', $entity->name);
                }
                foreach ($relations as $relation) {
                    $graph .= 'E'.$relation->source_id.' -> E'.$relation->destination_id.'[label="'.$relation->name.'"]';
                }
                $graph .= '}';

                // IMAGE
                $image_paths[] = $image_path = $this->generateGraphImage($graph);
                Html::addHtml($section, '<table style="width:100%"><tr><td><img src="'.$image_path.'" width="'.min(600, getimagesize($image_path)[0] / 2).'"/></td></tr></table>');
                $section->addTextBreak(1);
            }

            // ===============================
            $section->addTitle(trans('cruds.entity.title'), 2);
            $section->addText(trans('cruds.entity.description'));
            $section->addTextBreak(1);

            // loop on entities
            foreach ($entities as $entity) {
                $section->addBookmark('ENTITY'.$entity->id);
                $table = $this->addTable($section, $entity->name);
                $this->addHTMLRow($table, trans('cruds.entity.fields.description'), $entity->description);
                $this->addHTMLRow($table, trans('cruds.entity.fields.security_level'), $entity->security_level);
                $this->addHTMLRow($table, trans('cruds.entity.fields.contact_point'), $entity->contact_point);
                $this->addHTMLRow($table, trans('cruds.entity.fields.is_external'), $entity->is_external ? trans('global.yes') : trans('global.no'));
                $this->addHTMLRow($table, trans('cruds.entity.fields.entity_type'), $entity->entity_type);

                // Relations
                $textRun = $this->addTextRunRow($table, trans('cruds.entity.fields.relations'));
                foreach ($entity->sourceRelations as $relation) {
                    if ($relation->id !== null) {
                        $textRun->addLink('RELATION'.$relation->id, $relation->name, CartographyController::FANCYLINKSTYLE, CartographyController::NOSPACE);
                    }
                    $textRun->addText(' -> ');
                    if ($relation->destination_id !== null) {
                        $textRun->addLink('ENTITY'.$relation->destination_id, $relation->destination->name ?? '', CartographyController::FANCYLINKSTYLE, CartographyController::NOSPACE, true);
                    }
                    if ($entity->sourceRelations->last() !== $relation) {
                        $textRun->addText(', ');
                    }
                }
                if ((count($entity->sourceRelations) > 0) && (count($entity->destinationRelations) > 0)) {
                    $textRun->addText(', ');
                }
                foreach ($entity->destinationRelations as $relation) {
                    $textRun->addLink('RELATION'.$relation->id, $relation->name, CartographyController::FANCYLINKSTYLE, CartographyController::NOSPACE, true);
                    $textRun->addText(' <- ');
                    $textRun->addLink('ENTITY'.$relation->source_id, $relation->source->name ?? '', CartographyController::FANCYLINKSTYLE, CartographyController::NOSPACE, true);
                    if ($entity->destinationRelations->last() !== $relation) {
                        $textRun->addText(', ');
                    }
                }
                // Processus soutenus
                $textRun = $this->addTextRunRow($table, trans('cruds.entity.fields.processes'));
                foreach ($entity->processes as $process) {
                    $textRun->addLink('PROCESS'.$process->id, $process->name, CartographyController::FANCYLINKSTYLE, null, true);
                    if ($entity->processes->last() !== $process) {
                        $textRun->addText(', ');
                    }
                }
                $section->addTextBreak(1);
            }

            // ===============================
            $section->addTitle(trans('cruds.relation.title'), 2);
            $section->addText(trans('cruds.relation.description'));
            $section->addTextBreak(1);

            // Loop on relations
            foreach ($relations as $relation) {
                $section->addBookmark('RELATION'.$relation->id);
                $table = $this->addTable($section, $relation->name);
                $this->addHTMLRow($table, trans('cruds.relation.fields.description'), $relation->description);

                if ($granularity > 1) {
                    $this->addTextRow($table, trans('cruds.relation.fields.type'), $relation->type);
                    $textRun = $this->addTextRow(
                        $table,
                        trans('cruds.relation.fields.importance'),
                        $relation->importance === null ?
                            '-' :
                        ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$relation->importance] ?? '')
                    );
                }
                $textRun = $this->addTextRunRow($table, trans('cruds.relation.fields.link'));
                $textRun->addLink('ENTITY'.$relation->source_id, $relation->source->name ?? '', CartographyController::FANCYLINKSTYLE, CartographyController::NOSPACE, true);
                $textRun->addText(' -> ');
                $textRun->addLink('ENTITY'.$relation->destination_id, $relation->destination->name ?? '', CartographyController::FANCYLINKSTYLE, CartographyController::NOSPACE, true);
                $section->addTextBreak(1);
            }
        }

        // =====================
        // SYSTEME D'INFORMATION
        // =====================
        if ($vues === null || count($vues) === 0 || in_array('2', $vues)) {
            $section->addTitle(trans('cruds.report.cartography.information_system'), 1);
            $section->addText(trans('cruds.menu.metier.description'));
            $section->addTextBreak(1);

            // Get data
            $macroProcessuses = MacroProcessus::orderBy('name')->get();
            $processes = Process::orderBy('name')->get();
            $activities = Activity::orderBy('name')->get();
            $operations = Operation::orderBy('name')->get();
            $tasks = Task::orderBy('name')->get();
            $actors = Actor::orderBy('name')->get();
            $informations = Information::orderBy('name')->get();

            // Generate Graph
            if ($request->has('graph')) {
                $graph = 'digraph  {';
                if ($granularity >= 2) {
                    foreach ($macroProcessuses as $macroProcess) {
                        $graph .= ' MP'.$macroProcess->id.$this->dotImage('/images/macroprocess.png', $macroProcess->name);
                        foreach ($macroProcess->processes as $process) {
                            $graph .= ' MP'.$macroProcess->id.'->P'.$process->id;
                        }
                    }
                }

                foreach ($processes as $process) {
                    $graph .= ' P'.$process->id.$this->dotImage('/images/process.png', $process->name);
                    if ($granularity === 3) {
                        foreach ($process->activities as $activity) {
                            $graph .= ' P'.$process->id.'->A'.$activity->id;
                        }
                    }
                    foreach ($process->information as $information) {
                        $graph .= ' P'.$process->id.'->I'.$information->id;
                    }
                }
                if ($granularity === 3) {
                    foreach ($activities as $activity) {
                        $graph .= ' A'.$activity->id.$this->dotImage('/images/activity.png', $activity->name);
                        foreach ($activity->operations as $operation) {
                            $graph .= ' A'.$activity->id.'->O'.$operation->id;
                        }
                    }
                }
                foreach ($operations as $operation) {
                    $graph .= ' O'.$operation->id.$this->dotImage('/images/operation.png', $operation->name);
                    if ($granularity === 3) {
                        foreach ($operation->tasks as $task) {
                            $graph .= ' O'.$operation->id.'->T'.$task->id;
                        }
                    }
                    if ($granularity >= 2) {
                        foreach ($operation->actors as $actor) {
                            $graph .= ' O'.$operation->id.'->ACT'.$actor->id;
                        }
                    }
                }
                if ($granularity === 3) {
                    foreach ($tasks as $task) {
                        $graph .= ' T'.$task->id.$this->dotImage('/images/task.png', $task->name);
                    }
                }
                if ($granularity >= 2) {
                    foreach ($actors as $actor) {
                        $graph .= ' ACT'.$actor->id.$this->dotImage('/images/actor.png', $actor->name);
                    }
                }
                foreach ($informations as $information) {
                    $graph .= ' I'.$information->id.$this->dotImage('/images/information.png', $information->name);
                }
                $graph .= '}';

                // IMAGE
                $image_paths[] = $image_path = $this->generateGraphImage($graph);
                Html::addHtml($section, '<table style="width:100%"><tr><td><img src="'.$image_path.'" width="'.min(600, getimagesize($image_path)[0] / 2).'"/></td></tr></table>');
                $section->addTextBreak(1);
            }

            // =====================================
            if (($granularity >= 2) && ($macroProcessuses->count() > 0)) {
                $section->addTitle(trans('cruds.macroProcessus.title'), 2);
                $section->addText(trans('cruds.macroProcessus.description'));
                $section->addTextBreak(1);

                // Loop on relations
                foreach ($macroProcessuses as $macroProcess) {
                    $section->addBookmark('MACROPROCESS'.$macroProcess->id);
                    $table = $this->addTable($section, $macroProcess->name);
                    $this->addHTMLRow($table, trans('cruds.macroProcessus.fields.description'), $macroProcess->description);
                    $this->addHTMLRow($table, trans('cruds.macroProcessus.fields.io_elements'), $macroProcess->io_elements);
                    // Security Needs
                    $textRun = $this->addHTMLRow(
                        $table,
                        trans('cruds.macroProcessus.fields.security_need'),
                        '<p>'.
                            trans('global.confidentiality').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$macroProcess->security_need_c] ?? '').
                            '<br>'.
                            trans('global.integrity').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$macroProcess->security_need_i] ?? '').
                            '<br>'.
                            trans('global.availability').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$macroProcess->security_need_a] ?? '').
                            '<br>'.
                            trans('global.tracability').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$macroProcess->security_need_t] ?? '').
                            (config('mercator-config.parameters.security_need_auth') ?
                                    ('<br>'.
                                    trans('global.authenticity').
                                    ' : '.
                                    ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$macroProcess->security_need_auth] ?? ''))
                                :
                                    '').
                            '</p>'
                    );
                    // ---
                    if ($granularity >= 3) {
                        $this->addTextRow($table, trans('cruds.macroProcessus.fields.owner'), $macroProcess->owner);
                    }
                    $textRun = $this->addTextRunRow($table, trans('cruds.macroProcessus.fields.processes'));
                    foreach ($macroProcess->processes as $process) {
                        $textRun->addLink('PROCESS'.$process->id, $process->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($macroProcess->processes->last() !== $process) {
                            $textRun->addText(', ');
                        }
                    }
                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('process_show') && ($processes->count() > 0)) {
                $section->addTitle(trans('cruds.process.title'), 2);
                $section->addText(trans('cruds.process.description'));
                $section->addTextBreak(1);

                foreach ($processes as $process) {
                    $section->addBookmark('PROCESS'.$process->id);
                    $table = $this->addTable($section, $process->name);
                    $this->addHTMLRow($table, trans('cruds.process.fields.description'), $process->description);
                    $this->addHTMLRow($table, trans('cruds.process.fields.in_out'), $process->in_out);
                    $textRun = $this->addTextRunRow($table, trans('cruds.process.fields.activities'));
                    foreach ($process->activities as $activity) {
                        $textRun->addLink('ACTIVITY'.$activity->id, $activity->name, CartographyController::FANCYLINKSTYLE, CartographyController::NOSPACE, true);
                        if ($process->activities->last() !== $activity) {
                            $textRun->addText(', ');
                        }
                    }
                    $textRun = $this->addTextRunRow($table, trans('cruds.process.fields.entities'));
                    foreach ($process->entities as $entity) {
                        $textRun->addLink('ENTITY'.$entity->id, $entity->name, CartographyController::FANCYLINKSTYLE, CartographyController::NOSPACE, true);
                        if ($process->entities->last() !== $entity) {
                            $textRun->addText(', ', CartographyController::FANCYRIGHTTABLECELLSTYLE, CartographyController::NOSPACE);
                        }
                    }
                    $textRun = $this->addTextRunRow($table, trans('cruds.process.fields.applications'));
                    foreach ($process->applications as $application) {
                        $textRun->addLink('APPLICATION'.$application->id, $application->name, CartographyController::FANCYLINKSTYLE, CartographyController::NOSPACE, true);
                        if ($process->applications->last() !== $application) {
                            $textRun->addText(', ', CartographyController::FANCYRIGHTTABLECELLSTYLE, CartographyController::NOSPACE);
                        }
                    }
                    // Security Needs
                    $textRun = $this->addHTMLRow(
                        $table,
                        trans('cruds.process.fields.security_need'),
                        '<p>'.
                            trans('global.confidentiality').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$process->security_need_c] ?? '').
                            '<br>'.
                            trans('global.integrity').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$process->security_need_i] ?? '').
                            '<br>'.
                            trans('global.availability').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$process->security_need_a] ?? '').
                            '<br>'.
                            trans('global.tracability').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$process->security_need_t] ?? '').
                            (config('mercator-config.parameters.security_need_auth') ?
                                    ('<br>'.
                                    trans('global.authenticity').
                                    ' : '.
                                    ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$process->security_need_auth] ?? ''))
                                :
                                    '').
                            '</p>'
                    );
                    // Owner
                    $this->addTextRow($table, trans('cruds.process.fields.owner'), $process->owner);
                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('activity_show') && ($activities->count() > 0) && ($granularity === 3)) {
                $section->addTitle(trans('cruds.activity.title'), 2);
                $section->addText(trans('cruds.activity.description'));
                $section->addTextBreak(1);

                foreach ($activities as $activity) {
                    $section->addBookmark('ACTIVITY'.$activity->id);
                    $table = $this->addTable($section, $activity->name);
                    $this->addHTMLRow($table, trans('cruds.activity.fields.description'), $activity->description);

                    $textRun = $this->addTextRunRow($table, trans('cruds.activity.fields.operations'));
                    foreach ($activity->operations as $operation) {
                        $textRun->addLink('OPERATION'.$operation->id, $operation->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($activity->operations->last() !== $operation) {
                            $textRun->addText(', ');
                        }
                    }
                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('operation_show') && ($operations->count() > 0)) {
                $section->addTitle(trans('cruds.operation.title'), 2);
                $section->addText(trans('cruds.operation.description'));
                $section->addTextBreak(1);

                foreach ($operations as $operation) {
                    $section->addBookmark('OPERATION'.$operation->id);
                    $table = $this->addTable($section, $operation->name);
                    $this->addHTMLRow($table, trans('cruds.operation.fields.description'), $operation->description);
                    // Tâches
                    if ($granularity === 3) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.operation.fields.tasks'));
                        foreach ($operation->tasks as $task) {
                            $textRun->addLink('TASK'.$task->id, $task->name, CartographyController::FANCYLINKSTYLE, null, true);
                            if ($operation->tasks->last() !== $task) {
                                $textRun->addText(', ');
                            }
                        }
                    }
                    // Liste des acteurs qui interviennent
                    if ($granularity >= 2) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.operation.fields.actors'));
                        foreach ($operation->actors as $actor) {
                            $textRun->addLink('ACTOR'.$actor->id, $actor->name, CartographyController::FANCYLINKSTYLE, null, true);
                            if ($operation->actors->last() !== $actor) {
                                $textRun->addText(', ');
                            }
                        }
                    }
                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('task_show') && ($tasks->count() > 0) && ($granularity === 3)) {
                $section->addTitle(trans('cruds.task.title'), 2);
                $section->addText(trans('cruds.task.description'));
                $section->addTextBreak(1);

                foreach ($tasks as $task) {
                    $section->addBookmark('TASK'.$task->id);
                    $table = $this->addTable($section, $task->name);
                    $this->addHTMLRow($table, trans('cruds.task.fields.description'), $task->description);

                    // Operations
                    $textRun = $this->addTextRunRow($table, trans('cruds.task.fields.operations'));
                    foreach ($task->operations as $operation) {
                        $textRun->addLink('OPERATION'.$operation->id, $operation->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($task->operations->last() !== $operation) {
                            $textRun->addText(', ');
                        }
                    }
                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('actor_show') && ($actors->count() > 0) && ($granularity >= 2)) {
                $section->addTitle(trans('cruds.actor.title'), 2);
                $section->addText(trans('cruds.actor.description'));
                $section->addTextBreak(1);

                foreach ($actors as $actor) {
                    $section->addBookmark('ACTOR'.$actor->id);
                    $table = $this->addTable($section, $actor->name);
                    $this->addHTMLRow($table, trans('cruds.actor.fields.nature'), $actor->nature);
                    $this->addHTMLRow($table, trans('cruds.actor.fields.type'), $actor->type);

                    // Operations
                    $textRun = $this->addTextRunRow($table, trans('cruds.actor.fields.operations'));
                    foreach ($actor->operations as $operation) {
                        $textRun->addLink('OPERATION'.$operation->id, $operation->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($actor->operations->last() !== $operation) {
                            $textRun->addText(', ');
                        }
                    }
                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('information_show') && ($informations->count() > 0)) {
                $section->addTitle(trans('cruds.information.title'), 2);
                $section->addText(trans('cruds.information.description'));
                $section->addTextBreak(1);

                foreach ($informations as $information) {
                    $section->addBookmark('INFORMATION'.$information->id);
                    $table = $this->addTable($section, $information->name);
                    $this->addHTMLRow($table, trans('cruds.information.fields.description'), $information->description);
                    $this->addTextRow($table, trans('cruds.information.fields.owner'), $information->owner);
                    $this->addTextRow($table, trans('cruds.information.fields.administrator'), $information->administrator);
                    $this->addTextRow($table, trans('cruds.information.fields.storage'), $information->storage);
                    // processus liés
                    $textRun = $this->addTextRunRow($table, trans('cruds.information.fields.processes'));
                    foreach ($information->processes as $process) {
                        $textRun->addLink('PROCESS'.$process->id, $process->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($information->processes->last() !== $process) {
                            $textRun->addText(', ');
                        }
                    }
                    // Security Needs
                    $textRun = $this->addHTMLRow(
                        $table,
                        trans('cruds.information.fields.security_need'),
                        '<p>'.
                            trans('global.confidentiality').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$information->security_need_c] ?? '').
                            '<br>'.
                            trans('global.integrity').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$information->security_need_i] ?? '').
                            '<br>'.
                            trans('global.availability').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$information->security_need_a] ?? '').
                            '<br>'.
                            trans('global.tracability').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$information->security_need_t] ?? '').
                            (config('mercator-config.parameters.security_need_auth') ?
                                    ('<br>'.
                                    trans('global.authenticity').
                                    ' : '.
                                    ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$information->security_need_auth] ?? ''))
                                :
                                    '').
                            '</p>'
                    );

                    $this->addTextRow($table, trans('cruds.information.fields.sensitivity'), $information->sensitivity);

                    if ($granularity === 3) {
                        $this->addHTMLRow($table, trans('cruds.information.fields.constraints'), $information->constraints);
                    }

                    $section->addTextBreak(1);
                }
            }
        }

        // =====================
        // APPLICATIONS
        // =====================
        if (($vues === null) || count($vues) === 0 || in_array('3', $vues)) {
            $section->addTextBreak(2);
            $section->addTitle(trans('cruds.menu.application.title'), 1);
            $section->addText(trans('cruds.menu.application.description'));
            $section->addTextBreak(1);

            // get all data
            $applicationBlocks = ApplicationBlock::orderBy('name')->get();
            $applications = MApplication::orderBy('name')->get();
            $applicationServices = ApplicationService::orderBy('name')->get();
            $applicationModules = ApplicationModule::orderBy('name')->get();
            $databases = Database::orderBy('name')->get();
            $fluxes = Flux::orderBy('name')->get();

            // Generate Graph
            if ($request->has('graph')) {
                $graph = 'digraph  {';

                foreach ($applicationBlocks as $ab) {
                    $graph .= ' AB'.$ab->id.$this->dotImage('/images/applicationblock.png', $ab->name);
                }

                foreach ($applications as $application) {
                    $graph .= ' A'.$application->id.$this->dotImage('/images/application.png', $application->name);
                    foreach ($application->services as $service) {
                        $graph .= ' A'.$application->id.'->AS'.$service->id;
                    }
                    foreach ($application->databases as $database) {
                        $graph .= ' A'.$application->id.'->DB'.$database->id;
                    }
                    if ($application->application_block_id !== null) {
                        $graph .= ' AB'.$application->application_block_id.'->A'.$application->id;
                    }
                }
                foreach ($applicationServices as $service) {
                    $graph .= ' AS'.$service->id.$this->dotImage('/images/applicationservice.png', $service->name);
                    foreach ($service->modules as $module) {
                        $graph .= ' AS'.$service->id.'->M'.$module->id;
                    }
                }

                foreach ($applicationModules as $module) {
                    $graph .= ' M'.$module->id.$this->dotImage('/images/applicationmodule.png', $module->name);
                }

                foreach ($databases as $database) {
                    $graph .= ' DB'.$database->id.$this->dotImage('/images/database.png', $database->name);
                }

                $graph .= '}';

                // IMAGE
                $image_paths[] = $image_path = $this->generateGraphImage($graph);
                Html::addHtml($section, '<table style="width:100%"><tr><td><img src="'.$image_path.'" width="'.min(600, getimagesize($image_path)[0] / 2).'"/></td></tr></table>');
                $section->addTextBreak(1);
            }

            // =====================================
            if (Auth::user()->can('application_block_show') && ($applicationBlocks->count() > 0)) {
                $section->addTitle(trans('cruds.applicationBlock.title'), 2);
                $section->addText(trans('cruds.applicationBlock.description'));
                $section->addTextBreak(1);

                foreach ($applicationBlocks as $ab) {
                    $section->addBookmark('APPLICATIONBLOCK'.$ab->id);
                    $table = $this->addTable($section, $ab->name);
                    $this->addHTMLRow($table, trans('cruds.applicationBlock.fields.description'), $ab->description);
                    $this->addTextRow($table, trans('cruds.applicationBlock.fields.responsible'), $ab->responsible);

                    $textRun = $this->addTextRunRow($table, trans('cruds.applicationBlock.fields.applications'));
                    foreach ($ab->applications as $application) {
                        $textRun->addLink('APPLICATION'.$application->id, $application->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($ab->applications->last() !== $application) {
                            $textRun->addText(', ');
                        }
                    }
                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('m_application_show') && ($applications->count() > 0)) {
                $section->addTitle(trans('cruds.application.title'), 2);
                $section->addText(trans('cruds.application.description'));
                $section->addTextBreak(1);

                foreach ($applications as $application) {
                    $section->addBookmark('APPLICATION'.$application->id);
                    $table = $this->addTable($section, $application->name);
                    $this->addTextRow($table, trans('cruds.application.fields.version'), $application->version);

                    $this->addHTMLRow($table, trans('cruds.application.fields.description'), $application->description);

                    $textRun = $this->addTextRunRow($table, trans('cruds.application.fields.entities'));
                    foreach ($application->entities as $entity) {
                        $textRun->addLink('ENTITY'.$entity->id, $entity->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($application->entities->last() !== $entity) {
                            $textRun->addText(', ');
                        }
                    }

                    $textRun = $this->addTextRunRow($table, trans('cruds.application.fields.entity_resp'));
                    if ($application->entity_resp !== null) {
                        $textRun->addLink('ENTITY'.$application->entity_resp->id, $application->entity_resp->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }
                    $this->addTextRow($table, trans('cruds.application.fields.technology'), $application->technology);
                    $this->addTextRow($table, trans('cruds.application.fields.type'), $application->type);
                    $this->addTextRow($table, trans('cruds.application.fields.users'), $application->users);

                    $this->addTextRow($table, trans('cruds.application.fields.documentation'), $application->documentation);

                    $textRun = $this->addTextRunRow($table, trans('cruds.application.fields.flux'));
                    $textRun->addText(trans('cruds.flux.fields.source').' : ');
                    foreach ($application->applicationSourceFluxes as $flux) {
                        $textRun->addLink('FLUX'.$flux->id, $flux->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($application->applicationSourceFluxes->last() !== $flux) {
                            $textRun->addText(', ');
                        }
                    }
                    $textRun->addTextBreak(1);
                    $textRun->addText(trans('cruds.flux.fields.destination').' : ');
                    foreach ($application->applicationDestFluxes as $flux) {
                        $textRun->addLink('FLUX'.$flux->id, $flux->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($application->applicationDestFluxes->last() !== $flux) {
                            $textRun->addText(', ');
                        }
                    }

                    $this->addTextRow($table, trans('cruds.application.fields.install_date'), $application->install_date);
                    $this->addTextRow($table, trans('cruds.application.fields.update_date'), $application->update_date);

                    // Security Needs
                    $textRun = $this->addHTMLRow(
                        $table,
                        trans('cruds.application.fields.security_need'),
                        '<p>'.
                            trans('global.confidentiality').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$application->security_need_c] ?? '').
                            '<br>'.
                            trans('global.integrity').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$application->security_need_i] ?? '').
                            '<br>'.
                            trans('global.availability').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$application->security_need_a] ?? '').
                            '<br>'.
                            trans('global.tracability').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$application->security_need_t] ?? '').
                            (config('mercator-config.parameters.security_need_auth') ?
                                    ('<br>'.
                                    trans('global.authenticity').
                                    ' : '.
                                    ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$application->security_need_auth] ?? ''))
                                :
                                    '').
                            '</p>'
                    );

                    // RTO
                    $textRun = $this->addHTMLRow(
                        $table,
                        trans('cruds.application.fields.RTO'),
                        (intdiv($application->rto, 60 * 24) > 0 ?
                            (intdiv($application->rto, 60 * 24) .' '.
                                (intdiv($application->rto, 60 * 24) > 1 ? trans('global.days') : trans('global.day'))) : '').
                        (intdiv($application->rto, 60) % 24 > 0 ?
                            (strval(intdiv($application->rto, 60) % 24)).' '.
                                (intdiv($application->rto, 60) % 24 > 1 ? trans('global.hours') : trans('global.hour'))
                            : '').
                        ($application->rto % 60 > 0 ?
                            (strval($application->rto % 60)).' '.
                                ($application->rto % 60 > 1 ? trans('global.hours') : trans('global.hour'))
                            : '')
                    );

                    // RPO
                    $textRun = $this->addHTMLRow(
                        $table,
                        trans('cruds.application.fields.RPO'),
                        (intdiv($application->rpo, 60 * 24) > 0 ?
                            (intdiv($application->rpo, 60 * 24) .' '.
                                (intdiv($application->rpo, 60 * 24) > 1 ? trans('global.days') : trans('global.day'))) : '').
                        (intdiv($application->rpo, 60) % 24 > 0 ?
                            (strval(intdiv($application->rpo, 60) % 24)).' '.
                                (intdiv($application->rpo, 60) % 24 > 1 ? trans('global.hours') : trans('global.hour'))
                            : '').
                        ($application->rpo % 60 > 0 ?
                            (strval($application->rpo % 60)).' '.
                                ($application->rpo % 60 > 1 ? trans('global.hours') : trans('global.hour'))
                            : '')
                    );

                    $this->addTextRow($table, trans('cruds.application.fields.external'), $application->external);

                    $textRun = $this->addTextRunRow($table, trans('cruds.application.fields.processes'));
                    foreach ($application->processes as $process) {
                        $textRun->addLink('PROCESS'.$process->id, $process->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($application->processes->last() !== $process) {
                            $textRun->addText(', ');
                        }
                    }

                    $textRun = $this->addTextRunRow($table, trans('cruds.application.fields.activities'));
                    foreach ($application->activities as $activity) {
                        $textRun->addLink('ACTIVITY'.$activity->id, $activity->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($application->activities->last() !== $activity) {
                            $textRun->addText(', ');
                        }
                    }

                    $textRun = $this->addTextRunRow($table, trans('cruds.application.fields.services'));
                    foreach ($application->services as $service) {
                        $textRun->addLink('APPLICATIONSERVICE'.$service->id, $service->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($application->services->last() !== $service) {
                            $textRun->addText(', ');
                        }
                    }

                    $textRun = $this->addTextRunRow($table, trans('cruds.application.fields.databases'));
                    foreach ($application->databases as $database) {
                        $textRun->addLink('DATABASE'.$database->id, $database->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($application->databases->last() !== $database) {
                            $textRun->addText(', ');
                        }
                    }

                    $textRun = $this->addTextRunRow($table, trans('cruds.application.fields.application_block'));
                    if ($application->application_block !== null) {
                        $textRun->addLink('APPLICATIONBLOCK'.$application->application_block_id, $application->application_block->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    $textRun = $this->addTextRunRow($table, trans('cruds.application.fields.logical_servers'));
                    foreach ($application->logicalServers as $logical_server) {
                        $textRun->addLink('LOGICAL_SERVER'.$logical_server->id, $logical_server->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($application->logicalServers->last() !== $logical_server) {
                            $textRun->addText(', ');
                        }
                    }

                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('application_service_show') && ($applicationServices->count() > 0)) {
                $section->addTitle(trans('cruds.applicationService.title'), 2);
                $section->addText(trans('cruds.applicationService.description'));
                $section->addTextBreak(1);

                foreach ($applicationServices as $applicationService) {
                    $section->addBookmark('APPLICATIONSERVICE'.$applicationService->id);
                    $table = $this->addTable($section, $applicationService->name);
                    $this->addHTMLRow($table, trans('cruds.applicationService.fields.description'), $applicationService->description);

                    // Modules
                    $textRun = $this->addTextRunRow($table, trans('cruds.applicationService.fields.modules'));
                    foreach ($applicationService->modules as $module) {
                        $textRun->addLink('APPLICATIONMODULE'.$module->id, $module->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($applicationService->modules->last() !== $module) {
                            $textRun->addText(', ');
                        }
                    }

                    // Flux
                    $textRun = $this->addTextRunRow($table, trans('cruds.applicationService.fields.flux'));
                    $textRun->addText(trans('cruds.flux.fields.source').' : ');
                    foreach ($applicationService->serviceSourceFluxes as $flux) {
                        $textRun->addLink('FLUX'.$flux->id, $flux->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($applicationService->serviceSourceFluxes->last() !== $flux) {
                            $textRun->addText(', ');
                        }
                    }
                    $textRun->addTextBreak(1);
                    $textRun->addText(trans('cruds.flux.fields.destination').' : ');
                    foreach ($applicationService->serviceDestFluxes as $flux) {
                        $textRun->addLink('FLUX'.$flux->id, $flux->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($applicationService->serviceDestFluxes->last() !== $flux) {
                            $textRun->addText(', ');
                        }
                    }

                    $this->addTextRow($table, trans('cruds.applicationService.fields.exposition'), $applicationService->exposition);

                    // Applications
                    $textRun = $this->addTextRunRow($table, trans('cruds.applicationService.fields.applications'));
                    foreach ($applicationService->applications as $application) {
                        $textRun->addLink('APPLICATION'.$application->id, $application->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($applicationService->applications->last() !== $application) {
                            $textRun->addText(', ');
                        }
                    }
                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('application_module_show') && ($applicationModules->count() > 0)) {
                $section->addTitle(trans('cruds.applicationModule.title'), 2);
                $section->addText(trans('cruds.applicationModule.description'));
                $section->addTextBreak(1);

                foreach ($applicationModules as $applicationModule) {
                    $section->addBookmark('APPLICATIONMODULE'.$applicationModule->id);
                    $table = $this->addTable($section, $applicationModule->name);
                    $this->addHTMLRow($table, trans('cruds.applicationModule.fields.description'), $applicationModule->description);

                    // Services
                    $textRun = $this->addTextRunRow($table, trans('cruds.applicationModule.fields.services'));
                    foreach ($applicationModule->applicationServices as $service) {
                        $textRun->addLink('APPLICATIONSERVICE'.$service->id, $service->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($applicationModule->applicationServices->last() !== $service) {
                            $textRun->addText(', ');
                        }
                    }

                    // Flux
                    $textRun = $this->addTextRunRow($table, trans('cruds.applicationModule.fields.flux'));
                    $textRun->addText(trans('cruds.flux.fields.source').' : ');
                    foreach ($applicationModule->moduleSourceFluxes as $flux) {
                        $textRun->addLink('FLUX'.$flux->id, $flux->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($applicationModule->moduleSourceFluxes->last() !== $flux) {
                            $textRun->addText(', ');
                        }
                    }
                    $textRun->addTextBreak(1);
                    $textRun->addText(trans('cruds.flux.fields.destination').' : ');
                    foreach ($applicationModule->moduleDestFluxes as $flux) {
                        $textRun->addLink('FLUX'.$flux->id, $flux->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($applicationModule->moduleDestFluxes->last() !== $flux) {
                            $textRun->addText(', ');
                        }
                    }
                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('database_show') && ($databases->count() > 0)) {
                $section->addTitle(trans('cruds.database.title'), 2);
                $section->addText(trans('cruds.database.description'));
                $section->addTextBreak(1);

                foreach ($databases as $database) {
                    $section->addBookmark('DATABASE'.$database->id);
                    $table = $this->addTable($section, $database->name);
                    $this->addHTMLRow($table, trans('cruds.database.fields.description'), $database->description);

                    // Services
                    $textRun = $this->addTextRunRow($table, trans('cruds.database.fields.entities'));
                    foreach ($database->entities as $entity) {
                        $textRun->addLink('ENTITY'.$entity->id, $entity->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($database->entities->last() !== $entity) {
                            $textRun->addText(', ');
                        }
                    }

                    // entity_resp
                    $textRun = $this->addTextRunRow($table, trans('cruds.database.fields.entity_resp'));
                    if ($database->entity_resp !== null) {
                        $textRun->addLink('ENTITY'.$database->entity_resp->id, $database->entity_resp->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    $this->addTextRow($table, trans('cruds.database.fields.responsible'), $database->responsible);
                    $this->addTextRow($table, trans('cruds.database.fields.type'), $database->type);

                    // flows
                    $textRun = $this->addTextRunRow($table, trans('cruds.database.fields.flux'));
                    $textRun->addText(trans('cruds.flux.fields.source').' : ');
                    foreach ($database->databaseSourceFluxes as $flux) {
                        $textRun->addLink('FLUX'.$flux->id, $flux->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($database->databaseSourceFluxes->last() !== $flux) {
                            $textRun->addText(', ');
                        }
                    }
                    $textRun->addTextBreak(1);
                    $textRun->addText(trans('cruds.flux.fields.destination').' : ');
                    foreach ($database->databaseDestFluxes as $flux) {
                        $textRun->addLink('FLUX'.$flux->id, $flux->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($database->databaseDestFluxes->last() !== $flux) {
                            $textRun->addText(', ');
                        }
                    }

                    // Informations
                    $textRun = $this->addTextRunRow($table, trans('cruds.database.fields.informations'));
                    foreach ($database->informations as $information) {
                        $textRun->addLink('INFORMATION'.$information->id, $information->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($database->informations->last() !== $information) {
                            $textRun->addText(', ');
                        }
                    }

                    // Security Needs
                    $textRun = $this->addHTMLRow(
                        $table,
                        trans('cruds.database.fields.security_need'),
                        '<p>'.
                            trans('global.confidentiality').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$database->security_need_c] ?? '').
                            '<br>'.
                            trans('global.integrity').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$database->security_need_i] ?? '').
                            '<br>'.
                            trans('global.availability').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$database->security_need_a] ?? '').
                            '<br>'.
                            trans('global.tracability').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$database->security_need_t] ?? '').
                            (config('mercator-config.parameters.security_need_auth') ?
                                    ('<br>'.
                                    trans('global.authenticity').
                                    ' : '.
                                    ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$database->security_need_auth] ?? ''))
                                :
                                    '').
                            '</p>'
                    );

                    $this->addTextRow($table, trans('cruds.database.fields.external'), $database->external);

                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('logical_flow_show') && ($fluxes->count() > 0)) {
                $section->addTitle(trans('cruds.flux.title'), 2);
                $section->addText(trans('cruds.flux.description'));
                $section->addTextBreak(1);

                foreach ($fluxes as $flux) {
                    $section->addBookmark('FLUX'.$flux->id);
                    $table = $this->addTable($section, $flux->name);
                    $this->addHTMLRow($table, trans('cruds.flux.fields.description'), $flux->description);

                    // source
                    if ($flux->application_source !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.flux.fields.application_source'));
                        $textRun->addLink('APPLICATION'.$flux->application_source->id, $flux->application_source->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    if ($flux->service_source !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.flux.fields.service_source'));
                        $textRun->addLink('APPLICATIONSERVICE'.$flux->service_source->id, $flux->service_source->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    if ($flux->module_source !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.flux.fields.module_source'));
                        $textRun->addLink('APPLICATIONMODULE'.$flux->module_source->id, $flux->module_source->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    if ($flux->database_source !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.flux.fields.database_source'));
                        $textRun->addLink('DATABASE'.$flux->database_source->id, $flux->database_source->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    // Dest
                    if ($flux->application_dest !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.flux.fields.application_dest'));
                        $textRun->addLink('APPLICATION'.$flux->application_dest->id, $flux->application_dest->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    if ($flux->service_dest !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.flux.fields.service_dest'));
                        $textRun->addLink('APPLICATIONSERVICE'.$flux->service_dest->id, $flux->service_dest->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    if ($flux->module_dest !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.flux.fields.module_dest'));
                        $textRun->addLink('APPLICATIONMODULE'.$flux->module_dest->id, $flux->module_dest->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    if ($flux->database_dest !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.flux.fields.database_dest'));
                        $textRun->addLink('DATABASE'.$flux->database_dest->id, $flux->database_dest->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    $section->addTextBreak(1);
                }
            }
        }

        // =====================
        // Administration
        // =====================
        if ($vues === null || count($vues) === 0 || in_array('4', $vues)) {
            $section->addTextBreak(2);
            $section->addTitle(trans('cruds.menu.administration.title'), 1);
            $section->addText(trans('cruds.menu.administration.description'));
            $section->addTextBreak(1);

            // get all data
            $zones = ZoneAdmin::All();
            $annuaires = Annuaire::All();
            $forests = ForestAd::All();
            $domains = DomaineAd::All();

            // Generate Graph
            if ($request->has('graph')) {
                $graph = 'digraph  {';
                foreach ($zones as $zone) {
                    $graph .= ' Z'.$zone->id.$this->dotImage('/images/zoneadmin.png', $zone->name);
                    foreach ($zone->annuaires as $annuaire) {
                        $graph .= ' Z'.$zone->id.'->A'.$annuaire->id;
                    }
                    foreach ($zone->forestAds as $forest) {
                        $graph .= ' Z'.$zone->id.'->F'.$forest->id;
                    }
                }
                foreach ($annuaires as $annuaire) {
                    $graph .= ' A'.$annuaire->id.$this->dotImage('/images/annuaire.png', $annuaire->name);
                }
                foreach ($forests as $forest) {
                    $graph .= ' F'.$forest->id.$this->dotImage('/images/ldap.png', $forest->name);
                    foreach ($forest->domaines as $domain) {
                        $graph .= ' F'.$forest->id.'->D'.$domain->id;
                    }
                }
                foreach ($domains as $domain) {
                    $graph .= ' D'.$domain->id.$this->dotImage('/images/domain.png', $domain->name);
                }
                $graph .= '}';

                // IMAGE
                $image_paths[] = $image_path = $this->generateGraphImage($graph);
                Html::addHtml($section, '<table style="width:100%"><tr><td><img src="'.$image_path.'" width="'.min(600, getimagesize($image_path)[0] / 2).'"/></td></tr></table>');
                $section->addTextBreak(1);
            }

            // =====================================
            if (Auth::user()->can('zone_show') && ($zones->count() > 0)) {
                $section->addTitle(trans('cruds.zoneAdmin.title'), 2);
                $section->addText(trans('cruds.zoneAdmin.description'));
                $section->addTextBreak(1);

                foreach ($zones as $zone) {
                    $section->addBookmark('ZONE'.$zone->id);
                    $table = $this->addTable($section, $zone->name);
                    $this->addHTMLRow($table, trans('cruds.zoneAdmin.fields.description'), $zone->description);

                    // Annuaires
                    $textRun = $this->addTextRunRow($table, trans('cruds.zoneAdmin.fields.annuaires'));
                    foreach ($zone->annuaires as $annuaire) {
                        $textRun->addLink('ANNUAIRE'.$annuaire->id, $annuaire->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($zone->annuaires->last() !== $annuaire) {
                            $textRun->addText(', ');
                        }
                    }

                    // Forets
                    $textRun = $this->addTextRunRow($table, trans('cruds.zoneAdmin.fields.forests'));
                    foreach ($zone->forestAds as $forest) {
                        $textRun->addLink('FOREST'.$forest->id, $forest->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($zone->forestAds->last() !== $forest) {
                            $textRun->addText(', ');
                        }
                    }
                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('annuaire_show') && ($annuaires->count() > 0)) {
                $section->addTitle(trans('cruds.annuaire.title'), 2);
                $section->addText(trans('cruds.annuaire.description'));
                $section->addTextBreak(1);

                foreach ($annuaires as $annuaire) {
                    $section->addBookmark('ANNUAIRE'.$annuaire->id);
                    $table = $this->addTable($section, $annuaire->name);
                    $this->addHTMLRow($table, trans('cruds.annuaire.fields.description'), $annuaire->description);

                    $this->addTextRow($table, trans('cruds.annuaire.fields.solution'), $annuaire->solution);

                    // Zone d'administration
                    $textRun = $this->addTextRunRow($table, trans('cruds.annuaire.fields.zone_admin'));
                    if ($annuaire->zone_admin !== null) {
                        $textRun->addLink('ZONE'.$annuaire->zone_admin->id, $annuaire->zone_admin->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('forest_show') && ($forests->count() > 0)) {
                $section->addTitle(trans('cruds.forestAd.title'), 2);
                $section->addText(trans('cruds.forestAd.description'));
                $section->addTextBreak(1);

                foreach ($forests as $forest) {
                    $section->addBookmark('FOREST'.$forest->id);
                    $table = $this->addTable($section, $forest->name);
                    $this->addHTMLRow($table, trans('cruds.forestAd.fields.description'), $forest->description);

                    // Zone d'administration
                    $textRun = $this->addTextRunRow($table, trans('cruds.forestAd.fields.zone_admin'));
                    if ($forest->zone_admin !== null) {
                        $textRun->addLink('ZONE'.$forest->zone_admin->id, $forest->zone_admin->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    // Domaines
                    $textRun = $this->addTextRunRow($table, trans('cruds.forestAd.fields.domaines'));
                    foreach ($forest->domaines as $domain) {
                        $textRun->addLink('DOMAIN'.$domain->id, $domain->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($forest->domaines->last() !== $domain) {
                            $textRun->addText(', ');
                        }
                    }
                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('domain_show') && ($domains->count() > 0)) {
                $section->addTitle(trans('cruds.domaineAd.title'), 2);
                $section->addText(trans('cruds.domaineAd.description'));
                $section->addTextBreak(1);

                foreach ($domains as $domain) {
                    $section->addBookmark('DOMAIN'.$domain->id);
                    $table = $this->addTable($section, $domain->name);
                    $this->addHTMLRow($table, trans('cruds.domaineAd.fields.description'), $domain->description);

                    $this->addTextRow($table, trans('cruds.domaineAd.fields.domain_ctrl_cnt'), strval($domain->domain_ctrl_cnt));
                    $this->addTextRow($table, trans('cruds.domaineAd.fields.user_count'), strval($domain->user_count));
                    $this->addTextRow($table, trans('cruds.domaineAd.fields.machine_count'), strval($domain->machine_count));
                    $this->addTextRow($table, trans('cruds.domaineAd.fields.relation_inter_domaine'), $domain->relation_inter_domaine);

                    // FOREST
                    $textRun = $this->addTextRunRow($table, trans('cruds.domaineAd.fields.forestAds'));
                    foreach ($domain->forestAds as $forest) {
                        $textRun->addLink('FOREST'.$forest->id, $forest->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($domain->forestAds->last() !== $forest) {
                            $textRun->addText(', ');
                        }
                    }
                    $section->addTextBreak(1);
                }
            }
        }

        // =====================
        // Infrastructure logique
        // =====================
        if ($vues === null || count($vues) === 0 || in_array('5', $vues)) {
            $section->addTextBreak(2);
            $section->addTitle(trans('cruds.menu.logical_infrastructure.title'), 1);
            $section->addText(trans('cruds.menu.logical_infrastructure.description'));
            $section->addTextBreak(1);

            // Get all data
            $networks = Network::orderBy('name')->get();
            $subnetworks = Subnetwork::orderBy('name')->get();
            $gateways = Gateway::orderBy('name')->get();
            $externalConnectedEntities = ExternalConnectedEntity::orderBy('name')->get();
            $networkSwitches = NetworkSwitch::orderBy('name')->get();
            $routers = Router::orderBy('name')->get();
            $securityDevices = SecurityDevice::orderBy('name')->get();
            $dhcpServers = DhcpServer::orderBy('name')->get();
            $dnsservers = Dnsserver::orderBy('name')->get();
            $logicalServers = LogicalServer::orderBy('name')->get();
            $clusters = Cluster::orderBy('name')->get();
            $certificates = Certificate::orderBy('name')->get();
            $containers = Container::orderBy('name')->get();
            $vlans = Vlan::orderBy('name')->get();

            // Generate Graph
            if ($request->has('graph')) {
                $graph = 'digraph  {';
                foreach ($networks as $network) {
                    $graph .= ' NET'.$network->id.$this->dotImage('/images/cloud.png', $network->name);
                }
                foreach ($gateways as $gateway) {
                    $graph .= ' GATEWAY'.$gateway->id.$this->dotImage('/images/gateway.png', $gateway->name);
                }
                foreach ($subnetworks as $subnetwork) {
                    $graph .= ' SUBNET'.$subnetwork->id.$this->dotImage('/images/network.png', $subnetwork->name);
                    if ($subnetwork->vlan_id !== null) {
                        $graph .= ' SUBNET'.$subnetwork->id.'->VLAN'.$subnetwork->vlan_id;
                    }
                    if ($subnetwork->network_id !== null) {
                        $graph .= ' NET'.$subnetwork->network->id.'->SUBNET'.$subnetwork->id;
                    }
                    if ($subnetwork->gateway_id !== null) {
                        $graph .= ' SUBNET'.$subnetwork->id.'->GATEWAY'.$subnetwork->gateway_id;
                    }
                }

                foreach ($externalConnectedEntities as $entity) {
                    $graph .= ' E'.$entity->id.$this->dotImage('/images/entity.png', $entity->name);
                    if ($entity->network !== null) {
                        $graph .= ' NET'.$entity->network->id.'->E'.$entity->id;
                    }
                }

                foreach ($logicalServers as $logicalServer) {
                    $graph .= ' LOGICAL_SERVER'.$logicalServer->id.$this->dotImage('/images/server.png', $logicalServer->name);
                    if ($logicalServer->address_ip !== null) {
                        foreach ($subnetworks as $subnetwork) {
                            foreach (explode(',', $logicalServer->address_ip) as $address) {
                                if ($subnetwork->contains($address)) {
                                    $graph .= ' SUBNET'.$subnetwork->id.'->LOGICAL_SERVER'.$logicalServer->id;
                                }
                            }
                        }
                    }
                }

                foreach ($dhcpServers as $dhcpServer) {
                    $graph .= ' DHCP_SERVER'.$dhcpServer->id.$this->dotImage('/images/server.png', $dhcpServer->name);
                    if ($dhcpServer->address_ip !== null) {
                        foreach ($subnetworks as $subnetwork) {
                            if ($subnetwork->contains($dhcpServer->address_ip)) {
                                $graph .= ' SUBNET'.$subnetwork->id.'->DHCP_SERVER'.$dhcpServer->id;
                            }
                        }
                    }
                }

                foreach ($dnsservers as $dnsserver) {
                    $graph .= ' DNS_SERVER'.$dnsserver->id.$this->dotImage('/images/server.png', $dnsserver->name);
                    if ($dnsserver->address_ip !== null) {
                        foreach ($subnetworks as $subnetwork) {
                            if ($subnetwork->contains($dnsserver->address_ip)) {
                                $graph .= ' SUBNET'.$subnetwork->id.'->DNS_SERVER'.$dnsserver->id;
                            }
                        }
                    }
                }

                foreach ($certificates as $certificate) {
                    if ($certificate->logical_servers->count() > 0) {
                        $graph .= ' CERT'.$certificate->id.$this->dotImage('/images/certificate.png', $certificate->name);
                    }
                    foreach ($certificate->logical_servers as $logical_server) {
                        $graph .= ' LOGICAL_SERVER'.$logical_server->id.'->CERT'.$certificate->id;
                    }
                }

                foreach ($routers as $router) {
                    $graph .= ' R'.$router->id.$this->dotImage('/images/router.png', $router->name);
                    foreach ($subnetworks as $subnetwork) {
                        if (($router->ip_addresses !== null) && ($subnetwork->address !== null)) {
                            foreach (explode(',', $router->ip_addresses) as $address) {
                                if ($subnetwork->contains($address)) {
                                    $graph .= ' SUBNET'.$subnetwork->id.'->R'.$router->id;
                                }
                            }
                        }
                    }
                }

                foreach ($vlans as $vlan) {
                    $graph .= ' VLAN'.$vlan->id.$this->dotImage('/images/vlan.png', $vlan->name);
                }

                $graph .= '}';

                // IMAGE
                $image_paths[] = $image_path = $this->generateGraphImage($graph);
                Html::addHtml($section, '<table style="width:100%"><tr><td><img src="'.$image_path.'" width="'.min(600, getimagesize($image_path)[0] / 2).'"/></td></tr></table>');
                $section->addTextBreak(1);
            }

            // =====================================
            if (Auth::user()->can('network_show') && ($networks->count() > 0)) {
                $section->addTitle(trans('cruds.network.title'), 2);
                $section->addText(trans('cruds.network.description'));
                $section->addTextBreak(1);

                foreach ($networks as $network) {
                    $section->addBookmark('NETWORK'.$network->id);
                    $table = $this->addTable($section, $network->name);
                    $this->addHTMLRow($table, trans('cruds.network.fields.description'), $network->description);

                    $this->addTextRow($table, trans('cruds.network.fields.protocol_type'), $network->protocol_type);
                    $this->addTextRow($table, trans('cruds.network.fields.responsible'), $network->responsible);
                    $this->addTextRow($table, trans('cruds.network.fields.responsible_sec'), $network->responsible_sec);

                    // Security Needs
                    $textRun = $this->addHTMLRow(
                        $table,
                        trans('cruds.network.fields.security_need'),
                        '<p>'.
                            trans('global.confidentiality').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$network->security_need_c] ?? '').
                            '<br>'.
                            trans('global.integrity').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$network->security_need_i] ?? '').
                            '<br>'.
                            trans('global.availability').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$network->security_need_a] ?? '').
                            '<br>'.
                            trans('global.tracability').
                            ' : '.
                            ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$network->security_need_t] ?? '').
                            (config('mercator-config.parameters.security_need_auth') ?
                                    ('<br>'.
                                    trans('global.authenticity').
                                    ' : '.
                                    ([1 => trans('global.low'), 2 => trans('global.medium'), 3 => trans('global.strong'), 4 => trans('global.very_strong')][$network->security_need_auth] ?? ''))
                                :
                                    '').
                            '</p>'
                    );
                    // ----

                    // subnetworks
                    $textRun = $this->addTextRunRow($table, trans('cruds.network.fields.subnetworks'));
                    foreach ($network->subnetworks as $subnetwork) {
                        $textRun->addLink('SUBNET'.$subnetwork->id, $subnetwork->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($network->subnetworks->last() !== $subnetwork) {
                            $textRun->addText(', ');
                        }
                    }

                    // entités externes connectées
                    $textRun = $this->addTextRunRow($table, trans('cruds.network.fields.externalConnectedEntities'));
                    foreach ($network->externalConnectedEntities as $entity) {
                        $textRun->addLink('ENTITY'.$entity->id, $entity->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($network->externalConnectedEntities->last() !== $entity) {
                            $textRun->addText(', ');
                        }
                    }
                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('subnetwork_show') && ($subnetworks->count() > 0)) {
                $section->addTitle(trans('cruds.subnetwork.title'), 2);
                $section->addText(trans('cruds.subnetwork.description'));
                $section->addTextBreak(1);

                foreach ($subnetworks as $subnetwork) {
                    $section->addBookmark('SUBNET'.$subnetwork->id);
                    $table = $this->addTable($section, $subnetwork->name);
                    $this->addHTMLRow($table, trans('cruds.subnetwork.fields.description'), $subnetwork->description);
                    $this->addTextRow($table, trans('cruds.subnetwork.fields.address'), $subnetwork->address.' ( '.$subnetwork->ipRange().' )');
                    $this->addTextRow($table, trans('cruds.subnetwork.fields.default_gateway'), $subnetwork->default_gateway);
                    $this->addTextRow($table, trans('cruds.subnetwork.fields.zone'), $subnetwork->zone);
                    $this->addTextRow($table, trans('cruds.subnetwork.fields.ip_allocation_type'), $subnetwork->ip_allocation_type);
                    $this->addTextRow($table, trans('cruds.subnetwork.fields.dmz'), $subnetwork->dmz);
                    $this->addTextRow($table, trans('cruds.subnetwork.fields.wifi'), $subnetwork->wifi);
                    // VLAN
                    $textRun = $this->addTextRunRow($table, trans('cruds.subnetwork.fields.vlan'));
                    if ($subnetwork->vlan !== null) {
                        $textRun->addLink('VLAN'.$subnetwork->vlan->id, $subnetwork->vlan->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }
                    // gateway
                    $textRun = $this->addTextRunRow($table, trans('cruds.subnetwork.fields.gateway'));
                    if ($subnetwork->gateway !== null) {
                        $textRun->addLink('GATEWAY'.$subnetwork->gateway->id, $subnetwork->gateway->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    $this->addTextRow($table, trans('cruds.subnetwork.fields.responsible_exp'), $subnetwork->responsible_exp);

                    $section->addTextBreak(1);
                }
            }

            // =====================================

            if (Auth::user()->can('network_switch_show') && ($networkSwitches->count() > 0)) {
                $section->addTitle(trans('cruds.networkSwitch.title'), 2);
                $section->addText(trans('cruds.networkSwitch.description'));
                $section->addTextBreak(1);

                foreach ($networkSwitches as $networkSwitch) {
                    $section->addBookmark('NETWORK_SWITCH'.$networkSwitch->id);
                    $table = $this->addTable($section, $networkSwitch->name);
                    $this->addHTMLRow($table, trans('cruds.networkSwitch.fields.description'), $networkSwitch->description);
                    $this->addTextRow($table, trans('cruds.networkSwitch.fields.ip'), $networkSwitch->ip);

                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('routeur_show') && ($routers->count() > 0)) {
                $section->addTitle(trans('cruds.router.title'), 2);
                $section->addText(trans('cruds.router.description'));
                $section->addTextBreak(1);

                foreach ($routers as $router) {
                    $section->addBookmark('ROUTER'.$router->id);
                    $table = $this->addTable($section, $router->name);
                    $this->addHTMLRow($table, trans('cruds.router.fields.description'), $router->description);
                    $this->addTextRow($table, trans('cruds.router.fields.ip_addresses'), $router->ip_addresses);
                    $this->addHTMLRow($table, trans('cruds.router.fields.rules'), $router->rules);

                    $section->addTextBreak(1);
                }
            }

            // =====================================

            if (Auth::user()->can('security_device_show') && ($securityDevices->count() > 0)) {
                $section->addTitle(trans('cruds.securityDevice.title'), 2);
                $section->addText(trans('cruds.securityDevice.description'));
                $section->addTextBreak(1);

                foreach ($securityDevices as $securityDevice) {
                    $section->addBookmark('SEC_DEV'.$securityDevice->id);
                    $table = $this->addTable($section, $securityDevice->name);
                    $this->addHTMLRow($table, trans('cruds.securityDevice.fields.description'), $securityDevice->description);

                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('gateway_show') && ($gateways->count() > 0)) {
                $section->addTitle(trans('cruds.gateway.title'), 2);
                $section->addText(trans('cruds.gateway.description'));
                $section->addTextBreak(1);

                foreach ($gateways as $gateway) {
                    $section->addBookmark('GATEWAY'.$gateway->id);
                    $table = $this->addTable($section, $gateway->name);
                    $this->addHTMLRow($table, trans('cruds.gateway.fields.description'), $gateway->description);

                    $this->addTextRow($table, trans('cruds.gateway.fields.authentification'), $gateway->authentification);
                    $this->addTextRow($table, trans('cruds.gateway.fields.ip'), $gateway->ip);

                    // Réseau ratachés
                    $textRun = $this->addTextRunRow($table, trans('cruds.gateway.fields.subnetworks'));
                    foreach ($gateway->subnetworks as $subnetwork) {
                        $textRun->addLink('SUBNET'.$subnetwork->id, $subnetwork->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($gateway->subnetworks->last() !== $subnetwork) {
                            $textRun->addText(', ');
                        }
                    }

                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('external_connected_entity_show') && ($externalConnectedEntities->count() > 0)) {
                $section->addTitle(trans('cruds.externalConnectedEntity.title'), 2);
                $section->addText(trans('cruds.externalConnectedEntity.description'));
                $section->addTextBreak(1);

                foreach ($externalConnectedEntities as $entity) {
                    $section->addBookmark('EXTENTITY'.$entity->id);
                    $table = $this->addTable($section, $entity->name);

                    $textRun = $this->addTextRunRow($table, trans('cruds.externalConnectedEntity.fields.entity'));
                    if ($entity->entity_id !== null) {
                        $textRun->addLink('ENTITY'.$entity->entity->id, $entity->entity->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    $this->addTextRow($table, trans('cruds.externalConnectedEntity.fields.type'), $entity->type);
                    $this->addTextRow($table, trans('cruds.externalConnectedEntity.fields.contacts'), $entity->contacts);

                    $textRun = $this->addTextRunRow($table, trans('cruds.externalConnectedEntity.fields.network'));
                    if ($entity->network !== null) {
                        $textRun->addLink('NETWORK'.$entity->network->id, $entity->network->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }
                    $this->addTextRow($table, trans('cruds.externalConnectedEntity.fields.src'), $entity->src_desc.' '.$entity->src);
                    $this->addTextRow($table, trans('cruds.externalConnectedEntity.fields.dest'), $entity->dest_desc.' '.$entity->dest);

                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('cluster_show') && ($clusters->count() > 0)) {
                $section->addTitle(trans('cruds.cluster.title'), 2);
                $section->addText(trans('cruds.cluster.description'));
                $section->addTextBreak(1);

                foreach ($clusters as $cluster) {
                    $section->addBookmark('CLUSTER'.$cluster->id);
                    $table = $this->addTable($section, $cluster->name);
                    $this->addTextRow($table, trans('cruds.cluster.fields.type'), $cluster->type);
                    $this->addHTMLRow($table, trans('cruds.cluster.fields.description'), $cluster->description);
                    $this->addTextRow($table, trans('cruds.cluster.fields.address_ip'), $cluster->address_ip);
                    // Logical Servers
                    $textRun = $this->addTextRunRow($table, trans('cruds.cluster.fields.logical_servers'));
                    foreach ($cluster->logicalServers as $server) {
                        $textRun->addLink('LOGICAL_SERVER'.$server->id, $server->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($cluster->logicalServers->last() !== $server) {
                            $textRun->addText(', ');
                        }
                    }
                    // Physical Servers
                    $textRun = $this->addTextRunRow($table, trans('cruds.cluster.fields.physical_servers'));
                    foreach ($cluster->physicalServers as $server) {
                        $textRun->addLink('PHYSICAL_SERVER'.$server->id, $server->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($cluster->physicalServers->last() !== $server) {
                            $textRun->addText(', ');
                        }
                    }
                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('logical_server_show') && ($logicalServers->count() > 0)) {
                $section->addTitle(trans('cruds.logicalServer.title'), 2);
                $section->addText(trans('cruds.logicalServer.description'));
                $section->addTextBreak(1);

                foreach ($logicalServers as $logicalServer) {
                    $section->addBookmark('LOGICAL_SERVER'.$logicalServer->id);
                    $table = $this->addTable($section, $logicalServer->name);
                    $this->addHTMLRow($table, trans('cruds.logicalServer.fields.description'), $logicalServer->description);

                    $this->addTextRow($table, trans('cruds.logicalServer.fields.operating_system'), $logicalServer->operating_system);
                    $this->addTextRow($table, trans('cruds.logicalServer.fields.install_date'), $logicalServer->install_date);
                    $this->addTextRow($table, trans('cruds.logicalServer.fields.update_date'), $logicalServer->update_date);
                    $this->addTextRow($table, trans('cruds.logicalServer.fields.cpu'), $logicalServer->cpu);
                    $this->addTextRow($table, trans('cruds.logicalServer.fields.memory'), $logicalServer->memory);
                    $this->addTextRow($table, trans('cruds.logicalServer.fields.disk'), strval($logicalServer->disk));
                    $this->addTextRow($table, trans('cruds.logicalServer.fields.address_ip'), $logicalServer->address_ip);
                    $this->addTextRow($table, trans('cruds.logicalServer.fields.environment'), $logicalServer->environment);
                    $this->addTextRow($table, trans('cruds.logicalServer.fields.net_services'), $logicalServer->net_services);

                    $this->addHTMLRow($table, trans('cruds.logicalServer.fields.configuration'), $logicalServer->configuration);

                    // APPLICATIONS
                    $textRun = $this->addTextRunRow($table, trans('cruds.logicalServer.fields.applications'));
                    foreach ($logicalServer->applications as $application) {
                        $textRun->addLink('APPLICATION'.$application->id, $application->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($logicalServer->applications->last() !== $application) {
                            $textRun->addText(', ');
                        }
                    }

                    // Physical server
                    $textRun = $this->addTextRunRow($table, trans('cruds.logicalServer.fields.servers'));
                    foreach ($logicalServer->physicalServers as $server) {
                        $textRun->addLink('PSERVER'.$server->id, $server->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($logicalServer->physicalServers->last() !== $server) {
                            $textRun->addText(', ');
                        }
                    }

                    // Certificates
                    if ($logicalServer->certificates->count() > 0) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.logicalServer.fields.certificates'));

                        foreach ($logicalServer->certificates as $certificate) {
                            $textRun->addLink('CERTIFICATE'.$certificate->id, $certificate->name, CartographyController::FANCYLINKSTYLE, null, true);
                            if ($logicalServer->certificates->last() !== $certificate) {
                                $textRun->addText(', ');
                            }
                        }
                    }

                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('container_show') && ($containers->count() > 0)) {
                $section->addTitle(trans('cruds.container.title'), 2);
                $section->addText(trans('cruds.container.description'));
                $section->addTextBreak(1);

                foreach ($containers as $container) {
                    $section->addBookmark('CONTAINER'.$container->id);
                    $table = $this->addTable($section, $container->name);
                    $this->addTextRow($table, trans('cruds.container.fields.type'), $container->type);
                    $this->addHTMLRow($table, trans('cruds.container.fields.description'), $container->description);
                    // Logical Servers
                    $textRun = $this->addTextRunRow($table, trans('cruds.container.fields.logical_servers'));
                    foreach ($container->logicalServers as $server) {
                        $textRun->addLink('LOGICAL_SERVER'.$server->id, $server->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($container->logicalServers->last() !== $server) {
                            $textRun->addText(', ');
                        }
                    }
                    // Applications
                    $textRun = $this->addTextRunRow($table, trans('cruds.container.fields.applications'));
                    foreach ($container->applications as $application) {
                        $textRun->addLink('APPLICATION'.$application->id, $application->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($container->applications->last() !== $application) {
                            $textRun->addText(', ');
                        }
                    }
                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('dhcp_server_show') && ($dhcpServers->count() > 0)) {
                $section->addTitle(trans('cruds.dhcpServer.title'), 2);
                $section->addText(trans('cruds.dhcpServer.description'));
                $section->addTextBreak(1);

                foreach ($dhcpServers as $dhcpServer) {
                    $section->addBookmark('DHCP_SERVER'.$dhcpServer->id);
                    $table = $this->addTable($section, $dhcpServer->name);
                    $this->addHTMLRow($table, trans('cruds.dhcpServer.fields.description'), $dhcpServer->description);
                    $this->addTextRow($table, trans('cruds.dhcpServer.fields.address_ip'), $dhcpServer->address_ip);

                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('dnsserver_show') && ($dnsservers->count() > 0)) {
                $section->addTitle(trans('cruds.dnsserver.title'), 2);
                $section->addText(trans('cruds.dnsserver.description'));
                $section->addTextBreak(1);

                foreach ($dnsservers as $dnsserver) {
                    $section->addBookmark('DNS_SERVER'.$dnsserver->id);
                    $table = $this->addTable($section, $dnsserver->name);
                    $this->addHTMLRow($table, trans('cruds.dnsserver.fields.description'), $dnsserver->description);
                    $this->addTextRow($table, trans('cruds.dnsserver.fields.address_ip'), $dnsserver->address_ip);

                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('certificate_show') && ($certificates->count() > 0)) {
                $section->addTitle(trans('cruds.certificate.title'), 2);
                $section->addText(trans('cruds.certificate.description'));
                $section->addTextBreak(1);

                foreach ($certificates as $certificate) {
                    $section->addBookmark('CERTIFICATE'.$certificate->id);
                    $table = $this->addTable($section, $certificate->name);
                    $this->addTextRow($table, trans('cruds.certificate.fields.type'), $certificate->type);
                    $this->addHTMLRow($table, trans('cruds.certificate.fields.description'), $certificate->description);
                    $this->addTextRow($table, trans('cruds.certificate.fields.start_validity'), $certificate->start_validity);
                    $this->addTextRow($table, trans('cruds.certificate.fields.end_validity'), $certificate->end_validity);

                    // Logical Servers
                    if ($certificate->logical_servers->count() > 0) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.certificate.fields.logical_servers'));
                        foreach ($certificate->logical_servers as $logical_server) {
                            $textRun->addLink('LOGICAL_SERVER'.$logical_server->id, $logical_server->name, CartographyController::FANCYLINKSTYLE, null, true);
                            if ($certificate->logical_servers->last() !== $logical_server) {
                                $textRun->addText(', ');
                            }
                        }
                    }

                    // Applications
                    if ($certificate->applications->count() > 0) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.certificate.fields.applications'));
                        foreach ($certificate->applications as $application) {
                            $textRun->addLink('APPLICATION'.$application->id, $application->name, CartographyController::FANCYLINKSTYLE, null, true);
                            if ($certificate->applications->last() !== $application) {
                                $textRun->addText(', ');
                            }
                        }
                    }
                    $section->addTextBreak(1);
                }
            }
            // =====================================
            if (Auth::user()->can('vlan_show') && ($vlans->count() > 0)) {
                $section->addTitle(trans('cruds.vlan.title'), 2);
                $section->addText(trans('cruds.vlan.description'));
                $section->addTextBreak(1);

                foreach ($vlans as $vlan) {
                    $section->addBookmark('VLAN'.$vlan->id);
                    $table = $this->addTable($section, $vlan->name);
                    $this->addTextRow($table, trans('cruds.vlan.fields.vlan_id'), $vlan->vlan_id);
                    $this->addHTMLRow($table, trans('cruds.vlan.fields.description'), $vlan->description);

                    // Sous-réseaux
                    $textRun = $this->addTextRunRow($table, trans('cruds.vlan.fields.subnetworks'));
                    foreach ($vlan->subnetworks as $subnetwork) {
                        $textRun->addLink('SUBNET'.$subnetwork->id, $subnetwork->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($vlan->subnetworks->last() !== $subnetwork) {
                            $textRun->addText(', ');
                        }
                    }

                    $section->addTextBreak(1);
                }
            }
            // ==================================
        }

        // =====================
        // Infrastructure physique
        // =====================
        if ($vues === null || count($vues) === 0 || in_array('6', $vues)) {
            $section->addTextBreak(2);
            $section->addTitle(trans('cruds.menu.physical_infrastructure.title'), 1);
            $section->addText(trans('cruds.menu.physical_infrastructure.description'));
            $section->addTextBreak(1);

            // Get all data
            $sites = Site::orderBy('name')->get();
            $buildings = Building::orderBy('name')->get();
            $bays = Bay::orderBy('name')->get();
            $physicalServers = PhysicalServer::orderBy('name')->get();
            $workstations = Workstation::orderBy('name')->get();
            $storageDevices = StorageDevice::orderBy('name')->get();
            $peripherals = Peripheral::orderBy('name')->get();
            $phones = Phone::orderBy('name')->get();
            $physicalSwitches = PhysicalSwitch::orderBy('name')->get();
            $physicalRouters = PhysicalRouter::orderBy('name')->get();
            $wifiTerminals = WifiTerminal::orderBy('name')->get();
            $physicalSecurityDevices = PhysicalSecurityDevice::orderBy('name')->get();
            $wans = Wan::orderBy('name')->get();
            $mans = Man::orderBy('name')->get();
            $lans = Lan::orderBy('name')->get();

            // Generate Graph
            if ($request->has('graph')) {
                $graph = 'digraph  {';
                foreach ($sites as $site) {
                    $graph .= ' S'.$site->id.$this->dotImage('/images/site.png', $site->name);
                }
                foreach ($buildings as $building) {
                    $graph .= ' B'.$building->id.$this->dotImage('/images/building.png', $building->name);
                    $graph .= ' S'.$building->site_id.'->B'.$building->id;
                    foreach ($building->roomBays as $bay) {
                        $graph .= ' B'.$building->id.'->BAY'.$bay->id;
                    }
                }
                foreach ($bays as $bay) {
                    $graph .= ' BAY'.$bay->id.$this->dotImage('/images/bay.png', $bay->name);
                }
                foreach ($physicalServers as $pServer) {
                    $graph .= ' PSERVER'.$pServer->id.$this->dotImage('/images/server.png', $pServer->name);
                    if ($pServer->bay !== null) {
                        $graph .= ' BAY'.$pServer->bay->id.'->PSERVER'.$pServer->id;
                    } elseif ($pServer->building !== null) {
                        $graph .= ' B'.$pServer->building->id.'->PSERVER'.$pServer->id;
                    } elseif ($pServer->site !== null) {
                        $graph .= ' S'.$pServer->site->id.'->PSERVER'.$pServer->id;
                    }
                }
                foreach ($workstations as $workstation) {
                    $graph .= ' W'.$workstation->id.$this->dotImage('/images/workstation.png', $workstation->name);
                    if ($workstation->building !== null) {
                        $graph .= ' B'.$workstation->building->id.'->W'.$workstation->id;
                    } elseif ($workstation->site !== null) {
                        $graph .= ' S'.$workstation->site->id.'->W'.$workstation->id;
                    }
                }
                foreach ($storageDevices as $storageDevice) {
                    $graph .= ' SD'.$storageDevice->id.$this->dotImage('/images/storage.png', $storageDevice->name);
                    if ($storageDevice->bay !== null) {
                        $graph .= ' BAY'.$storageDevice->bay->id.'->SD'.$storageDevice->id;
                    } elseif ($storageDevice->building !== null) {
                        $graph .= ' B'.$storageDevice->building->id.'->SD'.$storageDevice->id;
                    } elseif ($storageDevice->site !== null) {
                        $graph .= ' S'.$storageDevice->site->id.'->SD'.$storageDevice->id;
                    }
                }
                foreach ($peripherals as $peripheral) {
                    $graph .= ' PER'.$peripheral->id.$this->dotImage('/images/peripheral.png', $peripheral->name);
                    if ($peripheral->bay !== null) {
                        $graph .= ' BAY'.$peripheral->bay->id.'->PER'.$peripheral->id;
                    } elseif ($peripheral->building !== null) {
                        $graph .= ' B'.$peripheral->building->id.'->PER'.$peripheral->id;
                    } elseif ($peripheral->site !== null) {
                        $graph .= ' S'.$peripheral->site->id.'->PER'.$peripheral->id;
                    }
                }
                foreach ($phones as $phone) {
                    $graph .= ' PHONE'.$phone->id.$this->dotImage('/images/phone.png', $phone->name);
                    if ($phone->building !== null) {
                        $graph .= ' B'.$phone->building->id.'->PHONE'.$phone->id;
                    } elseif ($phone->site !== null) {
                        $graph .= ' S'.$phone->site->id.'->PHONE'.$phone->id;
                    }
                }
                foreach ($physicalSwitches as $switch) {
                    $graph .= ' SWITCH'.$switch->id.$this->dotImage('/images/switch.png', $switch->name);
                    if ($switch->bay !== null) {
                        $graph .= ' BAY'.$switch->bay->id.'->SWITCH'.$switch->id;
                    } elseif ($switch->building !== null) {
                        $graph .= ' B'.$switch->building->id.'->SWITCH'.$switch->id;
                    } elseif ($switch->site !== null) {
                        $graph .= ' S'.$switch->site->id.'->SWITCH'.$switch->id;
                    }
                }
                foreach ($physicalRouters as $router) {
                    $graph .= ' ROUTER'.$router->id.$this->dotImage('/images/router.png', $router->name);
                    if ($router->bay !== null) {
                        $graph .= ' BAY'.$router->bay->id.'->ROUTER'.$router->id;
                    } elseif ($router->building !== null) {
                        $graph .= ' B'.$router->building->id.'->ROUTER'.$router->id;
                    } elseif ($router->site !== null) {
                        $graph .= ' S'.$router->site->id.'->ROUTER'.$router->id;
                    }
                }
                foreach ($wifiTerminals as $wifiTerminal) {
                    $graph .= ' WIFI'.$wifiTerminal->id.$this->dotImage('/images/wifi.png', $wifiTerminal->name);
                    if ($wifiTerminal->building !== null) {
                        $graph .= ' B'.$wifiTerminal->building->id.'->WIFI'.$wifiTerminal->id;
                    } elseif ($wifiTerminal->site !== null) {
                        $graph .= ' S'.$wifiTerminal->site->id.'->WIFI'.$wifiTerminal->id;
                    }
                }
                $graph .= '}';

                // IMAGE
                $image_paths[] = $image_path = $this->generateGraphImage($graph);
                Html::addHtml($section, '<table style="width:100%"><tr><td><img src="'.$image_path.'" width="'.min(600, getimagesize($image_path)[0] / 2).'"/></td></tr></table>');
                $section->addTextBreak(1);
            }

            // =====================================
            if (Auth::user()->can('site_show') && ($sites->count() > 0)) {
                $section->addTitle(trans('cruds.site.title'), 2);
                $section->addText(trans('cruds.site.description'));
                $section->addTextBreak(1);

                foreach ($sites as $site) {
                    $section->addBookmark('SITE'.$site->id);
                    $table = $this->addTable($section, $site->name);
                    $this->addHTMLRow($table, trans('cruds.site.fields.description'), $site->description);

                    // Buildings
                    $textRun = $this->addTextRunRow($table, trans('cruds.site.fields.buildings'));
                    foreach ($site->buildings as $building) {
                        $textRun->addLink('BUILDING'.$building->id, $building->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($site->buildings->last() !== $building) {
                            $textRun->addText(', ');
                        }
                    }

                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('building_show') && ($buildings->count() > 0)) {
                $section->addTitle(trans('cruds.building.title'), 2);
                $section->addText(trans('cruds.building.description'));
                $section->addTextBreak(1);

                foreach ($buildings as $building) {
                    $section->addBookmark('BUILDING'.$building->id);
                    $table = $this->addTable($section, $building->name);
                    $this->addHTMLRow($table, trans('cruds.building.fields.description'), $building->description);
                    $this->addHTMLRow($table, trans('cruds.building.fields.attributes'), $building->attributes);

                    // Baies
                    if ($building->roomBays->count() > 0) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.building.fields.bays'));
                        foreach ($building->roomBays as $bay) {
                            $textRun->addLink('BAY'.$bay->id, $bay->name, CartographyController::FANCYLINKSTYLE, null, true);
                            if ($building->roomBays->last() !== $bay) {
                                $textRun->addText(', ');
                            }
                        }
                    }
                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('bay_show') && ($bays->count() > 0)) {
                $section->addTitle(trans('cruds.bay.title'), 2);
                $section->addText(trans('cruds.bay.description'));
                $section->addTextBreak(1);

                foreach ($bays as $bay) {
                    $section->addBookmark('BAY'.$bay->id);
                    $table = $this->addTable($section, $bay->name);
                    $this->addHTMLRow($table, trans('cruds.bay.fields.description'), $bay->description);

                    // Serveurs physiques
                    if ($bay->bayPhysicalServers->count() > 0) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.bay.fields.physical_servers'));
                        foreach ($bay->bayPhysicalServers as $physicalServer) {
                            $textRun->addLink('PSERVER'.$physicalServer->id, $physicalServer->name, CartographyController::FANCYLINKSTYLE, null, true);
                            if ($bay->bayPhysicalServers->last() !== $physicalServer) {
                                $textRun->addText(', ');
                            }
                        }
                    }

                    // PhysicalRouters
                    if ($bay->bayPhysicalRouters->count() > 0) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.bay.fields.physical_routers'));
                        foreach ($bay->bayPhysicalRouters as $physicalRouter) {
                            $textRun->addLink('ROUTER'.$physicalRouter->id, $physicalRouter->name, CartographyController::FANCYLINKSTYLE, null, true);
                            if ($bay->bayPhysicalRouters->last() !== $physicalRouter) {
                                $textRun->addText(', ');
                            }
                        }
                    }

                    // Physical Switches
                    if ($bay->bayPhysicalSwitches->count() > 0) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.bay.fields.physical_switches'));
                        foreach ($bay->bayPhysicalSwitches as $physicalSwitch) {
                            $textRun->addLink('SWITCH'.$physicalSwitch->id, $physicalSwitch->name, CartographyController::FANCYLINKSTYLE, null, true);
                            if ($bay->bayPhysicalSwitches->last() !== $physicalSwitch) {
                                $textRun->addText(', ');
                            }
                        }
                    }

                    // Storage Devices
                    if ($bay->bayStorageDevices->count() > 0) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.bay.fields.storage_devices'));
                        foreach ($bay->bayStorageDevices as $storageDevice) {
                            $textRun->addLink('STORAGEDEVICE'.$storageDevice->id, $storageDevice->name, CartographyController::FANCYLINKSTYLE, null, true);
                            if ($bay->bayStorageDevices->last() !== $storageDevice) {
                                $textRun->addText(', ');
                            }
                        }
                    }

                    // PhysicalSecurityDevices
                    if ($bay->bayPhysicalSecurityDevices->count() > 0) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.bay.fields.physical_security_devices'));
                        foreach ($bay->bayPhysicalSecurityDevices as $physicalSecurityDevice) {
                            $textRun->addLink('PSD'.$physicalSecurityDevice->id, $physicalSecurityDevice->name, CartographyController::FANCYLINKSTYLE, null, true);
                            if ($bay->bayPhysicalSecurityDevices->last() !== $physicalSecurityDevice) {
                                $textRun->addText(', ');
                            }
                        }
                    }

                    // Peripherals
                    if ($bay->bayPeripherals->count() > 0) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.bay.fields.peripherals'));
                        foreach ($bay->bayPeripherals as $peripheral) {
                            $textRun->addLink('PERIPHERAL'.$peripheral->id, $peripheral->name, CartographyController::FANCYLINKSTYLE, null, true);
                            if ($bay->bayPeripherals->last() !== $peripheral) {
                                $textRun->addText(', ');
                            }
                        }
                    }

                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('physical_server_show') && ($physicalServers->count() > 0)) {
                $section->addTitle(trans('cruds.physicalServer.title'), 2);
                $section->addText(trans('cruds.physicalServer.description'));
                $section->addTextBreak(1);

                foreach ($physicalServers as $server) {
                    $section->addBookmark('PSERVER'.$server->id);
                    $table = $this->addTable($section, $server->name);
                    $this->addHTMLRow($table, trans('cruds.physicalServer.fields.description'), $server->description);
                    $this->addHTMLRow($table, trans('cruds.physicalServer.fields.configuration'), $server->configuration);

                    if ($server->site !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.physicalServer.fields.site'));
                        $textRun->addLink('SITE'.$server->site->id, $server->site->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    if ($server->building !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.physicalServer.fields.building'));
                        $textRun->addLink('BUILDING'.$server->building->id, $server->building->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    if ($server->bay !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.physicalServer.fields.bay'));
                        $textRun->addLink('BAY'.$server->bay->id, $server->bay->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    $this->addTextRow($table, trans('cruds.physicalServer.fields.responsible'), $server->responsible);

                    // Serveurs logiques
                    $textRun = $this->addTextRunRow($table, trans('cruds.physicalServer.fields.logical_servers'));
                    foreach ($server->logicalServers as $logicalServer) {
                        $textRun->addLink('LOGICAL_SERVER'.$logicalServer->id, $logicalServer->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($server->logicalServers->last() !== $logicalServer) {
                            $textRun->addText(', ');
                        }
                    }

                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('workstation_show') && ($workstations->count() > 0)) {
                $section->addTitle(trans('cruds.workstation.title'), 2);
                $section->addText(trans('cruds.workstation.description'));
                $section->addTextBreak(1);

                foreach ($workstations as $workstation) {
                    $section->addBookmark('WORKSTATION'.$workstation->id);
                    $table = $this->addTable($section, $workstation->name);
                    $this->addHTMLRow($table, trans('cruds.workstation.fields.type'), $workstation->type);
                    $this->addHTMLRow($table, trans('cruds.workstation.fields.description'), $workstation->description);
                    $this->addHTMLRow($table, trans('cruds.workstation.fields.operating_system'), $workstation->operating_system);
                    $this->addHTMLRow($table, trans('cruds.workstation.fields.address_ip'), $workstation->address_ip);
                    $textRun = $this->addTextRunRow($table, trans('cruds.workstation.fields.applications'));
                    foreach ($workstation->applications as $application) {
                        $textRun->addLink('APPLICATION'.$application->id, $application->name, CartographyController::FANCYLINKSTYLE, CartographyController::NOSPACE, true);
                        if ($workstation->applications->last() !== $application) {
                            $textRun->addText(', ', CartographyController::FANCYRIGHTTABLECELLSTYLE, CartographyController::NOSPACE);
                        }
                    }

                    $this->addHTMLRow($table, trans('cruds.workstation.fields.cpu'), $workstation->cpu);
                    $this->addHTMLRow($table, trans('cruds.workstation.fields.memory'), $workstation->memory);
                    $this->addHTMLRow($table, trans('cruds.workstation.fields.disk'), $workstation->disk);

                    if ($workstation->site !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.workstation.fields.site'));
                        $textRun->addLink('SITE'.$workstation->site->id, $workstation->site->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    if ($workstation->building !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.workstation.fields.building'));
                        $textRun->addLink('BUILDING'.$workstation->building->id, $workstation->building->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('storage_device_show') && ($storageDevices->count() > 0)) {
                $section->addTitle(trans('cruds.storageDevice.title'), 2);
                $section->addText(trans('cruds.storageDevice.description'));
                $section->addTextBreak(1);

                foreach ($storageDevices as $storageDevice) {
                    $section->addBookmark('STORAGEDEVICE'.$storageDevice->id);
                    $table = $this->addTable($section, $storageDevice->name);
                    $this->addHTMLRow($table, trans('cruds.storageDevice.fields.description'), $storageDevice->description);

                    if ($storageDevice->site !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.storageDevice.fields.site'));
                        $textRun->addLink('SITE'.$storageDevice->site->id, $storageDevice->site->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    if ($storageDevice->building !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.storageDevice.fields.building'));
                        $textRun->addLink('BUILDING'.$storageDevice->building->id, $storageDevice->building->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    if ($storageDevice->bay !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.storageDevice.fields.bay'));
                        $textRun->addLink('BAY'.$storageDevice->bay->id, $storageDevice->bay->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('peripheral_show') && ($peripherals->count() > 0)) {
                $section->addTitle(trans('cruds.peripheral.title'), 2);
                $section->addText(trans('cruds.peripheral.description'));
                $section->addTextBreak(1);

                foreach ($peripherals as $peripheral) {
                    $section->addBookmark('PERIPHERAL'.$peripheral->id);
                    $table = $this->addTable($section, $peripheral->name);
                    $this->addHTMLRow($table, trans('cruds.peripheral.fields.description'), $peripheral->description);

                    $this->addTextRow($table, trans('cruds.peripheral.fields.type'), $peripheral->type);
                    $this->addTextRow($table, trans('cruds.peripheral.fields.responsible'), $peripheral->responsible);

                    if ($peripheral->site !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.peripheral.fields.site'));
                        $textRun->addLink('SITE'.$peripheral->site->id, $peripheral->site->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    if ($peripheral->building !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.peripheral.fields.building'));
                        $textRun->addLink('BUILDING'.$peripheral->building->id, $peripheral->building->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    if ($peripheral->bay !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.peripheral.fields.bay'));
                        $textRun->addLink('BAY'.$peripheral->bay->id, $peripheral->bay->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('phone_show') && ($phones->count() > 0)) {
                $section->addTitle(trans('cruds.phone.title'), 2);
                $section->addText(trans('cruds.phone.description'));
                $section->addTextBreak(1);

                foreach ($phones as $phone) {
                    $section->addBookmark('PHONE'.$phone->id);
                    $table = $this->addTable($section, $phone->name);
                    $this->addHTMLRow($table, trans('cruds.phone.fields.description'), $phone->description);

                    $this->addTextRow($table, trans('cruds.phone.fields.type'), $phone->type);

                    if ($phone->site !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.phone.fields.site'));
                        $textRun->addLink('SITE'.$phone->site->id, $phone->site->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    if ($phone->building !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.phone.fields.building'));
                        $textRun->addLink('BUILDING'.$phone->building->id, $phone->building->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('physical_switch_show') && ($physicalSwitches->count() > 0)) {
                $section->addTitle(trans('cruds.physicalSwitch.title'), 2);
                $section->addText(trans('cruds.physicalSwitch.description'));
                $section->addTextBreak(1);

                foreach ($physicalSwitches as $switch) {
                    $section->addBookmark('SWITCH'.$switch->id);
                    $table = $this->addTable($section, $switch->name);
                    $this->addHTMLRow($table, trans('cruds.physicalSwitch.fields.description'), $switch->description);

                    $this->addTextRow($table, trans('cruds.physicalSwitch.fields.type'), $switch->type);

                    if ($switch->site !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.physicalSwitch.fields.site'));
                        $textRun->addLink('SITE'.$switch->site->id, $switch->site->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    if ($switch->building !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.physicalSwitch.fields.building'));
                        $textRun->addLink('BUILDING'.$switch->building->id, $switch->building->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    if ($switch->bay !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.physicalSwitch.fields.bay'));
                        $textRun->addLink('BAY'.$switch->bay->id, $switch->bay->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('physical_router_show') && ($physicalRouters->count() > 0)) {
                $section->addTitle(trans('cruds.physicalRouter.title'), 2);
                $section->addText(trans('cruds.physicalRouter.description'));
                $section->addTextBreak(1);

                foreach ($physicalRouters as $router) {
                    $section->addBookmark('ROUTER'.$router->id);
                    $table = $this->addTable($section, $router->name);
                    $this->addHTMLRow($table, trans('cruds.physicalRouter.fields.description'), $router->description);

                    $this->addTextRow($table, trans('cruds.physicalRouter.fields.type'), $router->type);

                    if ($router->site !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.physicalRouter.fields.site'));
                        $textRun->addLink('SITE'.$router->site->id, $router->site->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    if ($router->building !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.physicalRouter.fields.building'));
                        $textRun->addLink('BUILDING'.$router->building->id, $router->building->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    if ($router->bay !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.physicalRouter.fields.bay'));
                        $textRun->addLink('BAY'.$router->bay->id, $router->bay->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    $section->addTextBreak(1);
                }
            }
            // =====================================
            if (Auth::user()->can('wifi_terminal_show') && ($wifiTerminals->count() > 0)) {
                $section->addTitle(trans('cruds.wifiTerminal.title'), 2);
                $section->addText(trans('cruds.wifiTerminal.description'));
                $section->addTextBreak(1);

                foreach ($wifiTerminals as $wifiTerminal) {
                    $section->addBookmark('WIFI'.$wifiTerminal->id);
                    $table = $this->addTable($section, $wifiTerminal->name);
                    $this->addHTMLRow($table, trans('cruds.wifiTerminal.fields.description'), $wifiTerminal->description);

                    $this->addTextRow($table, trans('cruds.wifiTerminal.fields.type'), $wifiTerminal->type);

                    if ($wifiTerminal->site !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.wifiTerminal.fields.site'));
                        $textRun->addLink('SITE'.$wifiTerminal->site->id, $wifiTerminal->site->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    if ($wifiTerminal->building !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.wifiTerminal.fields.building'));
                        $textRun->addLink('BUILDING'.$wifiTerminal->building->id, $wifiTerminal->building->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    $section->addTextBreak(1);
                }
            }
            // =====================================
            if (Auth::user()->can('physical_security_device_show') && ($physicalSecurityDevices->count() > 0)) {
                $section->addTitle(trans('cruds.physicalSecurityDevice.title'), 2);
                $section->addText(trans('cruds.physicalSecurityDevice.description'));
                $section->addTextBreak(1);

                foreach ($physicalSecurityDevices as $physicalSecurityDevice) {
                    $section->addBookmark('PSD'.$physicalSecurityDevice->id);
                    $table = $this->addTable($section, $physicalSecurityDevice->name);
                    $this->addHTMLRow($table, trans('cruds.physicalSecurityDevice.fields.description'), $physicalSecurityDevice->description);

                    $this->addTextRow($table, trans('cruds.physicalSecurityDevice.fields.type'), $physicalSecurityDevice->type);

                    if ($physicalSecurityDevice->site !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.physicalSecurityDevice.fields.site'));
                        $textRun->addLink('SITE'.$physicalSecurityDevice->site->id, $physicalSecurityDevice->site->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    if ($physicalSecurityDevice->building !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.physicalSecurityDevice.fields.building'));
                        $textRun->addLink('BUILDING'.$physicalSecurityDevice->building->id, $physicalSecurityDevice->building->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    if ($physicalSecurityDevice->bay !== null) {
                        $textRun = $this->addTextRunRow($table, trans('cruds.physicalSecurityDevice.fields.bay'));
                        $textRun->addLink('BAY'.$physicalSecurityDevice->bay->id, $physicalSecurityDevice->bay->name, CartographyController::FANCYLINKSTYLE, null, true);
                    }

                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('wan_show') && ($wans->count() > 0)) {
                $section->addTitle(trans('cruds.wan.title'), 2);
                $section->addText(trans('cruds.wan.description'));
                $section->addTextBreak(1);

                foreach ($wans as $wan) {
                    $section->addBookmark('WAN'.$wan->id);
                    $table = $this->addTable($section, $wan->name);

                    // Mans
                    $textRun = $this->addTextRunRow($table, trans('cruds.wan.fields.mans'));
                    foreach ($wan->mans as $man) {
                        $textRun->addLink('MAN'.$man->id, $man->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($wan->mans->last() !== $man) {
                            $textRun->addText(', ');
                        }
                    }

                    // Lans
                    $textRun = $this->addTextRunRow($table, trans('cruds.wan.fields.lans'));
                    foreach ($wan->lans as $lan) {
                        $textRun->addLink('LAN'.$lan->id, $lan->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($wan->lans->last() !== $lan) {
                            $textRun->addText(', ');
                        }
                    }

                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('man_show') && ($mans->count() > 0)) {
                $section->addTitle(trans('cruds.man.title'), 2);
                $section->addText(trans('cruds.man.description'));
                $section->addTextBreak(1);

                foreach ($mans as $man) {
                    $section->addBookmark('MAN'.$man->id);
                    $table = $this->addTable($section, $man->name);
                    $this->addHTMLRow($table, trans('cruds.man.fields.description'), $man->description);

                    // Lans
                    $textRun = $this->addTextRunRow($table, 'LANs');
                    foreach ($man->lans as $lan) {
                        $textRun->addLink(trans('cruds.man.fields.lan').$lan->id, $lan->name, CartographyController::FANCYLINKSTYLE, null, true);
                        if ($man->lans->last() !== $lan) {
                            $textRun->addText(', ');
                        }
                    }

                    $section->addTextBreak(1);
                }
            }

            // =====================================
            if (Auth::user()->can('lan_show') && ($lans->count() > 0)) {
                $section->addTitle(trans('cruds.lan.title'), 2);
                $section->addText(trans('cruds.lan.description'));
                $section->addTextBreak(1);

                foreach ($lans as $lan) {
                    $section->addBookmark('LAN'.$lan->id);
                    $table = $this->addTable($section, $lan->name);
                    $this->addHTMLRow($table, trans('cruds.lan.fields.description'), $lan->description);

                    $section->addTextBreak(1);
                }
            }

            // =====================================
        }

        // Finename
        $filepath = storage_path('app/reports/cartographie-'.Carbon::today()->format('Ymd').'.docx');

        // Saving the document as Word2007 file.
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($filepath);

        // unlink the images
        foreach ($image_paths as $path) {
            unlink($path);
        }

        // return
        return response()->download($filepath)->deleteFileAfterSend(true);
    }

    private static function addTable(Section $section, ?string $title = null)
    {
        $table = $section->addTable(
            [
                'borderSize' => 2,
                'borderColor' => '006699',
                'cellMargin' => 80,
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            ]
        );
        $table->addRow();
        $table->addCell(8000, ['gridSpan' => 2])
            ->addText(
                $title,
                CartographyController::FANCYTABLETITLESTYLE,
                CartographyController::NOSPACE
            );

        return $table;
    }

    private static function addTextRow(Table $table, string $title, ?string $value = null): void
    {
        $table->addRow();
        $table->addCell(2000, CartographyController::NOSPACE)->addText($title, CartographyController::FANCYLEFTTABLECELLSTYLE, CartographyController::NOSPACE);
        $table->addCell(6000, CartographyController::NOSPACE)->addText($value, CartographyController::FANCYRIGHTTABLECELLSTYLE, CartographyController::NOSPACE);
    }

    private static function addHTMLRow(Table $table, string $title, ?string $value = null): void
    {
        $table->addRow();
        $table->addCell(2000)->addText($title, CartographyController::FANCYLEFTTABLECELLSTYLE, CartographyController::NOSPACE);
        try {
            \PhpOffice\PhpWord\Shared\Html::addHtml($table->addCell(6000), str_replace('<br>', '<br/>', $value));
        } catch (\Exception $e) {
            Log::error('CartographyController - Invalid HTML '.$value);
        }
    }

    private static function addTextRunRow(Table $table, string $title)
    {
        $table->addRow();
        $table->addCell(2000)->addText($title, CartographyController::FANCYLEFTTABLECELLSTYLE, CartographyController::NOSPACE);
        $cell = $table->addCell(6000);

        return $cell->addTextRun(CartographyController::FANCYRIGHTTABLECELLSTYLE);
    }

    /*
    // Return a base64 image from the path
    private function imageToBase64($path) {
        if (!file_exists($path)) {
            throw new Exception("Fichier introuvable : $path");
        }

        $imageData = file_get_contents($path);
        $mimeType = mime_content_type($path);
        $base64 = base64_encode($imageData);

        return "data:$mimeType;base64,$base64";
    }

    // Embed images in SVG
    private function embedImagesInSvg($svgPath) {
        if (!file_exists($svgPath)) {
            throw new Exception("Fichier SVG introuvable : $svgPath");
        }

        $svg = file_get_contents($svgPath);

        // Cherche les balises <image ... xlink:href="..." .../>
        $svg = preg_replace_callback(
            '/(<image[^>]+xlink:href=")([^"]+)(")/i',
            function ($matches) use ($svgPath) {
                $prefix = $matches[1];
                $imagePath = $matches[2];
                $suffix = $matches[3];

                if (!$imagePath || !file_exists($imagePath)) {
                    echo "⚠️ Image non trouvée : $imagePath\n";
                    return $matches[0]; // laisser inchangé
                }

                try {
                    $dataUri = $this->imageToBase64($imagePath);
                    return $prefix . $dataUri . $suffix;
                } catch (Exception $e) {
                    echo "Erreur : " . $e->getMessage() . "\n";
                    return $matches[0];
                }
            },
            $svg
        );

        // Sauvegarde le fichier modifié
        file_put_contents($svgPath, $svg);
    }
    */
    // Genetate the dot image
    private function dotImage(string $imagePath, string $name)
    {
        return '[shape=none label=<'.
              '<TABLE border="0" cellborder="0" cellspacing="0">'.
              '<TR><TD><IMG SRC="'.public_path($imagePath).'"/></TD></TR>'.
              '<TR><TD>'.$name.'</TD></TR>'.
              '</TABLE> >]';
    }

    // Generate the image of the graph from a dot notation using GraphViz
    private function generateGraphImage(string $graph)
    {
        // Save it to a file
        $dotPath = tempnam(sys_get_temp_dir(), 'dot');

        // \Log::Debug("generateGraphImage in {$dotPath}");

        try {
            $dotFile = fopen($dotPath, 'w');
            fwrite($dotFile, $graph);
            fclose($dotFile);

            // create image file
            $imagePath = tempnam(sys_get_temp_dir(), 'png');

            // Call DOT : dot -Tpng ./test.dot -otest.png
            // \Log::Debug("call /usr/bin/dot -Tpng -Gdpi=300 {$dotPath} -o{$imagePath}");

            // add "unset SERVER_NAME;" due to Apache2
            // see: https://github.com/glejeune/Ruby-Graphviz/issues/69
            shell_exec("unset SERVER_NAME; /usr/bin/dot -Tpng -Gdpi=300 {$dotPath} -o{$imagePath}");
        } finally {
            @unlink($dotPath);
        }
        // Test for SVG (not supported)
        // $this->embedImagesInSvg($imagePath);

        // return file path (do not forget to delete after...)
        return $imagePath;
    }
}
