<?php

namespace App\Http\Controllers\Admin;

Use \Carbon\Carbon;

// ecosystem
use App\Entity;
use App\Relation;

// information system
use App\MacroProcessus;
use App\Process;
use App\Activity;
use App\Operation;
use App\Task;
use App\Actor;
use App\Information;

// Applications
use App\ApplicationBlock;
use App\MApplication;
use App\ApplicationService;
use App\ApplicationModule;
use App\Database;
use App\Flux;

// Administration
use App\ZoneAdmin;
use App\Annuaire;
use App\ForestAd;
use App\DomaineAd;

// Logique
use App\Network;
use App\Subnetword;
use App\Gateway;
use App\ExternalConnectedEntity;
use App\NetworkSwitch;
use App\Router;
use App\SecurityDevice;
use App\DhcpServer;
use App\Dnsserver;
use App\LogicalServer;

// Physique
use App\Site;
use App\Building;
use App\Bay;
use App\PhysicalServer;
use App\Workstation;
use App\StorageDevice;
use App\Peripheral;
use App\Phone;
use App\PhysicalSwitch;
use App\PhysicalRouter;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// PhpOffice
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Shared\Converter;
use \PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Chart;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Line;

class CartographyController extends Controller
{
    // Cell style
    const FancyTableTitleStyle=array("bold"=>true, 'color' => '006699');
    const FancyLeftTableCellStyle=array("bold"=>true, 'color' => '000000');
    const FancyRightTableCellStyle=array("bold"=>false, 'color' => '000000');
    const FancyLinkStyle=array('color' => '006699');
    const NoSpace=array('spaceAfter' => 0);

    private static function addTable(Section $section, String $title=null) {
        $table = $section->addTable(
                array(
                    'borderSize' => 2, 
                    'borderColor' => '006699', 
                    'cellMargin' => 80,
                    'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER), 
                CartographyController::NoSpace);
        $table->addRow();
        $table->addCell(8000,array('gridSpan' => 2))
            ->addText($title,CartographyController::FancyTableTitleStyle, CartographyController::NoSpace);
        return $table;
    }

    private static function addTextRow(Table $table, String $title, String $value=null) {
        $table->addRow();
        $table->addCell(2000)->addText($title,CartographyController::FancyLeftTableCellStyle, CartographyController::NoSpace);
        $table->addCell(6000)->addText($value, CartographyController::FancyRightTableCellStyle, CartographyController::NoSpace);
    }

    private static function addHTMLRow(Table $table, String $title, String $value=null) { 
        $table->addRow();
        $table->addCell(2000)->addText($title, CartographyController::FancyLeftTableCellStyle, CartographyController::NoSpace);
        \PhpOffice\PhpWord\Shared\Html::addHtml($table->addCell(6000), str_replace("<br>", "<br/>", $value));
    }

    private static function addTextRunRow(Table $table, String $title) { 
        $table->addRow();
        $table->addCell(2000)->addText($title,CartographyController::FancyLeftTableCellStyle, CartographyController::NoSpace);
        $cell=$table->addCell(6000);
        return $cell->addTextRun(CartographyController::FancyRightTableCellStyle, CartographyController::NoSpace);
    }

    public function cartography(Request $request) {

        // converter 
        $html = new \PhpOffice\PhpSpreadsheet\Helper\Html();

        // Image paths
        $image_paths = array();

        // get parameters
        $granularity = $request->granularity;
        $vues = $request->input('vues', []);

        // get template
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        $phpWord->getSettings()->setHideGrammaticalErrors(true);
        $phpWord->getSettings()->setHideSpellingErrors(true);
        $section = $phpWord->addSection();

        // Numbering Style
        $phpWord->addNumberingStyle(
            'hNum',
            array('type' => 'multilevel', 'levels' => array(
                array('pStyle' => 'Heading1', 'format' => 'decimal', 'text' => '%1.'),
                array('pStyle' => 'Heading2', 'format' => 'decimal', 'text' => '%1.%2.'),
                array('pStyle' => 'Heading3', 'format' => 'decimal', 'text' => '%1.%2.%3.'),
                )
            )
        );
        $phpWord->addTitleStyle(0, 
                array('size' => 28, 'bold' => true), 
                array('align'=>'center'));
        $phpWord->addTitleStyle(1, 
                array('size' => 16, 'bold' => true), 
                array('numStyle' => 'hNum', 'numLevel' => 0));
        $phpWord->addTitleStyle(2, 
                array('size' => 14, 'bold' => true), 
                array('numStyle' => 'hNum', 'numLevel' => 1));
        $phpWord->addTitleStyle(3, 
                array('size' => 12, 'bold' => true), 
                array('numStyle' => 'hNum', 'numLevel' => 2));


        // Title
        $section->addTitle("Cartographie du Système d'Information",0);
        $section->addTextBreak(2);

        // TOC
        $toc = $section->addTOC(array('spaceAfter' => 60, 'size' => 10));
        $toc->setMinDepth(1);
        $toc->setMaxDepth(3);
        $section->addTextBreak(1);

        // page break
        $section->addPageBreak();

        // Add footer
        $footer = $section->addFooter();
        $footer->addPreserveText('Page {PAGE} of {NUMPAGES}', array('size' => 8) , array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
        // $footer->addLink('https://github.com/PHPOffice/PHPWord', 'PHPWord on GitHub');        

        // ====================
        // ==== Ecosystème ====
        // ====================        
        if ($vues==null || in_array("1",$vues)) {
            // schema
            $section->addTitle("Ecosystème", 1);
            $section->addText("La vue de l’écosystème décrit l’ensemble des entités ou systèmes qui gravitent autour du système d’information considéré dans le cadre de la cartographie. Cette vue permet à la fois de délimiter le périmètre de la cartographie, mais aussi de disposer d’une vision d’ensemble de l’écosystème sans se limiter à l’étude individuelle de chaque entité.");

            // Get data
            $entities = Entity::All()->sortBy("name");
            $relations = Relation::All()->sortBy("name");

            // Generate Graph
            $graph = "digraph  {";
            foreach($entities as $entity)
                $graph .= "E". $entity->id . "[label=\"". $entity->name ."\" shape=none labelloc=b width=1 height=1.8 image=\"".public_path("/images/entity.png")."\"]";
            foreach($relations as $relation) 
                $graph .= "E".$relation->source_id ." -> E". $relation->destination_id ."[label=\"". $relation ->name ."\"]";
            $graph .= "}";

            // IMAGE
            $image_paths[] = $image_path=$this->generateGraphImage($graph);
            Html::addHtml($section, '<table style="width:100%"><tr><td><img src="'.$image_path.'" width="'. min(600,getimagesize ($image_path)[0]/2) . '"/></td></tr></table>');
            $section->addTextBreak(1);

            // ===============================
            $section->addTitle('Entités', 2);
            $section->addText("Partie de l’organisme (ex. : filiale, département, etc.) ou système d’information en relation avec le SI qui vise à être cartographié.");
            $section->addTextBreak(1);

            // loop on entities
            foreach ($entities as $entity) {
                $section->addBookmark("ENTITY".$entity->id);
                $table = $this->addTable($section,$entity->name);
                $this->addHTMLRow($table,"Description",$entity->description);
                $this->addHTMLRow($table,"Niveau de sécurité",$entity->security_level);
                $this->addHTMLRow($table,"Point de contacl",$entity->contact_point);
                
                // Relations
                $textRun=$this->addTextRunRow($table,"Relations");
                foreach ($entity->sourceRelations as $relation) {
                    if ($relation->id!=null)
                        $textRun->addLink('RELATION'.$relation->id, $relation->name, CartographyController::FancyLinkStyle, null, true);
                    $textRun->addText(' -> ');
                    if ($relation->destination_id!=null)
                        $textRun->addLink('ENTITY'.$relation->destination_id, $entities->find($relation->destination_id)->name, CartographyController::FancyLinkStyle, null, true);
                    if ($entity->sourceRelations->last() != $relation) 
                        $textRun->addText(", ");                    
                }
                if ((count($entity->sourceRelations)>0)&&(count($entity->destinationRelations)>0))
                    $textRun->addText(", ");
                foreach ($entity->destinationRelations as $relation) {                    
                    $textRun->addLink('RELATION'.$relation->id, $relation->name, CartographyController::FancyLinkStyle, null, true);
                    $textRun->addText(' <- ');
                    $textRun->addLink('ENTITY'.$relation->source_id, $entities->find($relation->source_id)->name, CartographyController::FancyLinkStyle, null, true);
                    if ($entity->destinationRelations->last() != $relation)  
                        $textRun->addText(", ");
                }
                // Porcessus soutenus
                $textRun=$this->addTextRunRow($table,"Processus soutenus");
                foreach($entity->entitiesProcesses as $process) {
                    $textRun->addLink("PROCESS".$process->id, $process->identifiant);
                    if ($entity->entitiesProcesses->last() != $process)  
                        $textRun->addText(", ");
                    }
                $section->addTextBreak(1);
            }
                      
            // ===============================
            $section->addTitle("Relations", 2);
            $section->addText("Lien entre deux entités ou systèmes.");
            $section->addTextBreak(1);

            // Loop on relations
            foreach ($relations as $relation) {
                $section->addBookmark("RELATION".$relation->id);
                $table = $this->addTable($section,$relation->name);
                $this->addHTMLRow($table,"Description",$relation->description);

                if ($granularity>1) {
                    $this->addTextRow($table,"Type",$relation->type);
                    $textRun=$this->addTextRow($table,"Importance",
                        $relation->inportance==null ? 
                            "-" : 
                            array(1=>"Faible",2=>"Moyen",3=>"Fort",4=>"Critique")[$relation->inportance]);
                    }   
                $textRun=$this->addTextRunRow($table,"Lien");
                $textRun->addLink('ENTITY'.$relation->source_id, $entities->find($relation->source_id)->name, CartographyController::FancyLinkStyle, CartographyController::NoSpace, true);
                $textRun->addText(" -> ");
                $textRun->addLink('ENTITY'.$relation->destination_id, $entities->find($relation->destination_id)->name, CartographyController::FancyLinkStyle, CartographyController::NoSpace, true);
                $section->addTextBreak(1);
            }
        }
        
        // =====================
        // SYSTEME D'INFORMATION
        // =====================
        if ($vues==null || in_array("2",$vues)) {
            $section->addTitle("Système d'information", 1);
            $section->addText("La vue métier du système d’information décrit l’ensemble des processus métiers de l’organisme avec les acteurs qui y participent, indépendamment des choix technologiques faits par l’organisme et des ressources mises à sa disposition. La vue métier est essentielle, car elle permet de repositionner les éléments techniques dans leur environnement métier et ainsi de comprendre leur contexte d’emploi.");
            $section->addTextBreak(1);

            // Get data
            $macroProcessuses = MacroProcessus::All()->sortBy("name");
            $processes = Process::All()->sortBy("identifiant");
            $activities = Activity::All()->sortBy("name");
            $operations = Operation::All()->sortBy("name");
            $tasks = Task::All()->sortBy("nom");
            $actors = Actor::All()->sortBy("name");
            $informations = Information::All()->sortBy("name");

            // Generate Graph
            $graph = "digraph  {";
            if ($granularity>=2)
                foreach($macroProcessuses as $macroProcess) 
                    $graph .= " MP" . $macroProcess->id . " [label=\"". $macroProcess->name . "\" shape=none labelloc=b width=1 height=1.8 image=\"".public_path("/images/macroprocess.png")."\"]";
            
            foreach($processes as $process) {
                $graph .= " P".$process->id . " [label=\"" . $process->identifiant . "\" shape=none labelloc=b width=1 height=1.8 image=\"".public_path("/images/process.png")."\"]";
                if ($granularity==3)
                    foreach($process->activities as $activity)
                        $graph .= " P".$process->id . "->A". $activity->id;
                foreach($process->processInformation as $information) {
                    $graph .= " P". $process->id ."->I". $information->id;
                    if ($granularity>=2)
                        if ($process->macroprocess_id!=null)
                            $graph.=" MP" . $process->macroprocess_id ."-> P".$process->id;
                    }    
            }
            if ($granularity==3)
                foreach($activities as $activity) {
                    $graph .= " A" . $activity->id ." [label=\"". $activity->name ."\" shape=none labelloc=b width=1 height=1.8 image=\"".public_path("/images/activity.png")."\"]";
                    foreach($activity->operations as $operation)
                        $graph .= " A". $activity->id ."->O".$operation->id;
                    }
            foreach($operations as $operation) {
                $graph .= " O". $operation->id ." [label=\"". $operation->name ."\" shape=none labelloc=b width=1 height=1.8 image=\"".public_path("/images/operation.png")."\"]";
                if ($granularity==3)
                    foreach($operation->tasks as $task)
                        $graph .= " O" . $operation->id . "->T". $task->id;
                if ($granularity>=2)
                    foreach($operation->actors as $actor)
                        $graph .= " O". $operation->id . "->ACT". $actor->id;
                }
            if ($granularity==3)
                foreach($tasks as $task)
                    $graph .= " T". $task->id . " [label=\"". $task->nom . "\" shape=none labelloc=b width=1 height=1.8 image=\"". public_path("/images/task.png")."\"]";
            if ($granularity>=2)
                foreach($actors as $actor)
                    $graph .= " ACT". $actor->id . " [label=\"". $actor->name . "\" shape=none labelloc=b width=1 height=1.8 image=\"".public_path("/images/actor.png")."\"]";
            foreach($informations as $information)
                $graph .= " I". $information->id . " [label=\"" . $information->name . "\" shape=none labelloc=b width=1 height=1.8 image=\"".public_path("/images/information.png")."\"]";
            $graph .= "}";


            // IMAGE
            $image_paths[] = $image_path=$this->generateGraphImage($graph);
            Html::addHtml($section, '<table style="width:100%"><tr><td><img src="'.$image_path.'" width="'. min(600,getimagesize ($image_path)[0]/2) . '"/></td></tr></table>');
            $section->addTextBreak(1);

            // =====================================
            if (($granularity>=2)&&($macroProcessuses->count()>0)) {
                $section->addTitle('Macro-processus', 2);
                $section->addText("Les maro-processus représentent des ensembles de processus.");
                $section->addTextBreak(1);

                // Loop on relations
                foreach($macroProcessuses as $macroProcess) {
                    $section->addBookmark("MACROPROCESS".$macroProcess->id);
                    $table = $this->addTable($section, $macroProcess->name);
                    $this->addHTMLRow($table, "Description", $macroProcess->description);
                    $this->addHTMLRow($table, "Éléments entrants et sortants",$macroProcess->io_elements);
                    $textRun= $this->addTextRow($table, "Besoin de sécurité",
                        $macroProcess->security_need==null ? "-" : 
                            array(1=>"Public",2=>"Interne",3=>"Confidentiel",4=>"Secret")[$macroProcess->security_need]);
                    if ($granularity>=3)
                        $this->addTextRow($table, "Propritétaire",$macroProcess->owner);                
                    $textRun=$this->addTextRunRow($table, "Processus");
                    foreach($macroProcess->processes as $process) {
                        $textRun->addLink("PROCESS".$process->id, $process->identifiant, CartographyController::FancyLinkStyle, null, true);
                        if ($macroProcess->processes->last() != $process)  
                            $textRun->addText(", ");
                        }
                    $section->addTextBreak(1);
                    }
                }

            // =====================================
            if ($processes->count()>0) {
                $section->addTitle('Processus', 2);
                $section->addText("Ensemble d’activités concourant à un objectif. Le processus produit des informations (de sortie) à valeur ajoutée (sous forme de livrables) à partir d’informations (d’entrées) produites par d’autres processus.");
                $section->addTextBreak(1);

                foreach($processes as $process) {
                    $section->addBookmark("PROCESS".$process->id);                
                    $table=$this->addTable($section, $process->identifiant);
                    $this->addHTMLRow($table,"Description",$process->description);
                    $this->addHTMLRow($table,"Éléments entrants et sortants",$process->in_out);
                    $textRun=$this->addTextRunRow($table,"Activités");
                    foreach($process->activities as $activity) {
                        $textRun->addLink("ACTIVITY".$activity->id, $activity->name, CartographyController::FancyLinkStyle, CartographyController::NoSpace, true);
                        if ($process->activities->last() != $activity)  
                            $textRun->addText(", ");
                        }
                    $textRun=$this->addTextRunRow($table,"Entités associées");
                    foreach($process->entities as $entity) {
                        $textRun->addLink("ENTITY".$entity->id, $entity->name, CartographyController::FancyLinkStyle, CartographyController::NoSpace, true);
                        if ($process->entities->last() != $entity)  
                            $textRun->addText(", ", CartographyController::FancyRightTableCellStyle, CartographyController::NoSpace);
                        }
                    $textRun=$this->addTextRunRow($table,"Applications qui le soutiennent");
                    foreach($process->processesMApplications as $application) {
                        $textRun->addLink("APPLICATION".$application->id, $application->name, CartographyController::FancyLinkStyle, CartographyController::NoSpace, true);
                        if ($process->processesMApplications->last() != $application)  
                            $textRun->addText(", ", CartographyController::FancyRightTableCellStyle, CartographyController::NoSpace);
                        }
                    $textRun= $this->addTextRow($table, "Besoin de sécurité",
                        $process->security_need==null ? 
                            "-" : 
                            array(null=>"-",1=>"Faible",2=>"Moyen",3=>"Fort",4=>"Critique")[$process->security_need]);

                    $this->addTextRow($table,"Propriétaire",$process->owner);
                    $section->addTextBreak(1);
                    }
                }

            // =====================================
            if (($activities->count()>0)&&($granularity==3)) {
                $section->addTitle('Activités', 2);
                $section->addText("Étape nécessaire à la réalisation d’un processus. Elle correspond à un savoir-faire spéciﬁque et pas forcément à une structure organisationnelle de l’entreprise.");
                $section->addTextBreak(1);

                foreach($activities as $activity) {
                    $section->addBookmark("ACTIVITY".$activity->id);
                    $table=$this->addTable($section, $activity->name);
                    $this->addHTMLRow($table,"Description",$activity->description);

                    $textRun=$this->addTextRunRow($table,"Liste des opérations");
                    foreach($activity->operations as $operation) {
                        $textRun->addLink("OPERATION".$operation->id, $operation->name, CartographyController::FancyLinkStyle, null, true);
                        if ($activity->operations->last() != $operation)  
                            $textRun->addText(", ");
                        }
                    $section->addTextBreak(1);
                    }
                }

            // =====================================
            if ($operations->count()>0) {
                $section->addTitle('Opérations', 2);
                $section->addText("Étape d’une procédure correspondant à l’intervention d’un acteur dans le cadre d’une activité.");
                $section->addTextBreak(1); 

                foreach($operations as $operation) {
                    $section->addBookmark("OPERATION".$operation->id);                
                    $table=$this->addTable($section, $operation->name);
                    $this->addHTMLRow($table,"Description",$operation->description);
                    // Tâches
                    if ($granularity==3) { 
                        $textRun=$this->addTextRunRow($table,"Liste des tâches qui la composent");
                        foreach($operation->tasks as $task) {
                            $textRun->addLink("TASK".$task->id, $task->nom, CartographyController::FancyLinkStyle, null, true);
                            if ($operation->tasks->last() != $task)
                                $textRun->addText(", ");
                            }
                        }
                    // Liste des acteurs qui interviennent
                    if ($granularity>=2) { 
                        $textRun=$this->addTextRunRow($table,"Liste des acteurs qui interviennent");
                        foreach($operation->actors as $actor) {
                            $textRun->addLink("ACTOR".$actor->id, $actor->name, CartographyController::FancyLinkStyle, null, true);
                            if ($operation->actors->last() != $actor)
                                $textRun->addText(", ");
                            }
                        }
                    $section->addTextBreak(1);
                    }
                }

            // =====================================
            if (($tasks->count()>0)&&($granularity==3)) {
                $section->addTitle('Tâches', 2);
                $section->addText("Activité élémentaire exercée par une fonction organisationnelle et constituant une unité indivisible de travail dans la chaîne de valeur ajoutée d’un processus");
                $section->addTextBreak(1);

                foreach($tasks as $task) {
                    $section->addBookmark("TASK".$task->id);                
                    $table=$this->addTable($section, $task->nom);
                    $this->addHTMLRow($table,"Description",$task->description);

                    // Operations
                    $textRun=$this->addTextRunRow($table,"Liste des opérations");
                    foreach($task->operations as $operation) {
                        $textRun->addLink("OPERATION".$operation->id, $operation->name, CartographyController::FancyLinkStyle, null, true);
                        if ($task->operations->last() != $operation)
                            $textRun->addText(", ");
                        }
                    $section->addTextBreak(1);
                    }
                }                

            // =====================================
            if (($actors->count()>0)&&($granularity>=2)) { 
                $section->addTitle('Acteurs', 2);
                $section->addText("Représentant d’un rôle métier qui exécute des opérations, utilise des applications et prend des décisions dans le cadre des processus. Ce rôle peut être porté par une personne, un groupe de personnes ou une entité.");
                $section->addTextBreak(1); 

                foreach($actors as $actor) {
                    $section->addBookmark("ACTOR".$actor->id);                
                    $table=$this->addTable($section, $actor->name);
                    $this->addHTMLRow($table,"Nature",$actor->nature);
                    $this->addHTMLRow($table,"Type",$actor->type);

                    // Operations
                    $textRun=$this->addTextRunRow($table,"Liste des opérations");
                    foreach($actor->operations as $operation) {
                        $textRun->addLink("OPERATION".$operation->id, $operation->name, CartographyController::FancyLinkStyle, null, true);
                        if ($actor->operations->last() != $operation)
                            $textRun->addText(", ");
                        }
                    $section->addTextBreak(1);
                    }                
                }

            // =====================================
            if ($informations->count()>0) { 
                $section->addTitle('Informations', 2);
                $section->addText("Donnée faisant l’objet d’un traitement informatique.");
                $section->addTextBreak(1); 

                foreach($informations as $information) {
                    $section->addBookmark("INFORMATION".$information->id);
                    $table=$this->addTable($section, $information->name);
                    $this->addHTMLRow($table,"Description",$information->description);
                    $this->addTextRow($table,"Propriétaire",$information->owner);
                    $this->addTextRow($table,"Administrateur",$information->administrator);
                    $this->addTextRow($table,"Stockage",$information->storage);
                    // processus liés
                    $textRun=$this->addTextRunRow($table,"Processus liés");
                    foreach($information->processes as $process) {
                        $textRun->addLink("PROCESS".$process->id, $process->identifiant, CartographyController::FancyLinkStyle, null, true);
                        if ($information->processes->last() != $process)
                            $textRun->addText(", ");
                        }
                    $textRun=$this->addTextRunRow($table, "Besoin de sécurité");
                    if ($information->security_need==1)
                        $textRun->addText("Public");
                    elseif ($information->security_need==2) 
                        $textRun->addText("Interne");
                    elseif ($information->security_need==3) 
                        $textRun->addText("Confidentiel");
                    elseif ($information->security_need==4) 
                        $textRun->addText("Secret");
                    else
                        $textRun->addText("-");
                    $this->addTextRow($table,"Sensibilité",$information->sensibility);

                    if ($granularity==3)
                        $this->addHTMLRow($table,"Contraintes règlementaires et normatives",$information->constraints);

                    $section->addTextBreak(1);
                    }
                }
            }
        
        // =====================
        // APPLICATIONS
        // =====================
        if (($vues==null) || in_array("3",$vues)) {
            $section->addTextBreak(2);
            $section->addTitle("Applications", 1);
            $section->addText("La vue des applications décrit une partie de ce qui est classiquement appelé le « système informatique ». Cette vue décrit les solutions technologiques qui supportent les processus métiers, principalement les applications.");
            $section->addTextBreak(1);

            // get all data
            $applicationBlocks = ApplicationBlock::All()->sortBy("name");
            $applications = MApplication::All()->sortBy("name");
            $applicationServices = ApplicationService::All()->sortBy("name");
            $applicationModules = ApplicationModule::All()->sortBy("name");
            $databases = Database::All()->sortBy("name");
            $fluxes = Flux::All()->sortBy("name");

            // Generate Graph
            $graph = "digraph  {";

            foreach($applicationBlocks as $ab)
                $graph .= " AB" . $ab->id . " [label=\"".$ab->name."\" shape=none labelloc=b  width=1 height=1.8 image=\"" . public_path("/images/applicationblock.png")."\" ]";
            
            foreach($applications as $application) {
                $graph .= " A" . $application->id . "[label=\"" . $application->name ."\" shape=none labelloc=b width=1 height=1.8 image=\"" . public_path("/images/application.png") . "\"]";
                foreach($application->services as $service)
                    $graph .= " A" . $application->id ."->AS" . $service->id;
                foreach($application->databases as $database) 
                    $graph .= " A" . $application->id ."->DB" . $database->id;                
                if ($application->application_block_id!=null)
                    $graph .= " AB" . $application->application_block_id . "->A" .  $application->id;
                }
            foreach($applicationServices as $service) { 
                $graph .= " AS" . $service->id . "[label=\"" . $service->name . "\" shape=none labelloc=b width=1 height=1.8 image=\"" . public_path("/images/applicationservice.png") . "\"]";
                foreach($service->modules as $module) {
                    $graph .= " AS" . $service->id . "->M" . $module->id;
                }
            }
    
            foreach($applicationModules as $module) 
                $graph .= " M" . $module->id . "[label=\"" . $module->name . "\" shape=none labelloc=b width=1 height=1.8 image=\"" . public_path("/images/applicationmodule.png") ."\"]";

            foreach($databases as $database)
                $graph .= " DB" . $database->id . "[label=\"". $database->name . "\" shape=none labelloc=b width=1 height=1.8 image=\"" . public_path("/images/database.png") . "\"]";

            $graph .= "}";

            // IMAGE
            $image_paths[] = $image_path=$this->generateGraphImage($graph);
            Html::addHtml($section, '<table style="width:100%"><tr><td><img src="'.$image_path.'" width="'. min(600,getimagesize ($image_path)[0]/2) . '"/></td></tr></table>');
            $section->addTextBreak(1);

            // =====================================
            if ($applicationBlocks->count()>0) { 
                $section->addTitle('Blocs Applicatif', 2);
                $section->addText("Représente des ensembles d'applications.");
                $section->addTextBreak(1); 

                foreach($applicationBlocks as $ab) {
                    $section->addBookmark("APPLICATIONBLOCK".$ab->id);                
                    $table=$this->addTable($section, $ab->name);
                    $this->addHTMLRow($table,"Description",$ab->description);
                    $this->addTextRow($table,"Responsable",$ab->responsible);

                    $textRun=$this->addTextRunRow($table,"Applications");
                    foreach($ab->applications as $application) { 
                        $textRun->addLink("APPLICATION".$application->id, $application->name, CartographyController::FancyLinkStyle, null, true);
                        if ($ab->applications->last() != $application)
                            $textRun->addText(", ");
                        }
                    $section->addTextBreak(1); 
                    }
                }

            // =====================================
            if ($applications->count()>0) { 
                $section->addTitle('Applications', 2);
                $section->addText("Ensemble cohérent d’objets informatiques (exécutables, programmes, données...). Elle constitue un regroupement de services applicatifs.");
                $section->addTextBreak(1); 

                foreach($applications as $application) {
                    $section->addBookmark("APPLICATION".$application->id);                
                    $table=$this->addTable($section, $application->name);
                    $this->addHTMLRow($table,"Description",$application->description);
                    //
                    $textRun=$this->addTextRunRow($table,"Liste de la (des) entité(s) utilisatrice(s)");
                    foreach($application->entities as $entity) { 
                        $textRun->addLink("ENTITY".$entity->id, $entity->name, CartographyController::FancyLinkStyle, null, true);
                        if ($application->entities->last() != $entity)
                            $textRun->addText(", ");
                        }
                    //
                    $textRun=$this->addTextRunRow($table,"Entité responsable de l'exploitation");
                    if ($application->entity_resp!=null) 
                        $textRun->addLink("ENTITY".$application->entity_resp->id, $application->entity_resp->name, CartographyController::FancyLinkStyle, null, true);
                    $this->addTextRow($table,"Type de technologie",$application->technology);
                    $this->addTextRow($table,"Type d’application",$application->type);
                    $this->addTextRow($table,"Volume d’utilisateurs et profils",$application->users);
                    $this->addTextRow($table,"Documentation",$application->documentation);
                    //
                    $textRun=$this->addTextRunRow($table,"Flux associés");
                    $textRun->addText("Source : " );
                    foreach($application->applicationSourceFluxes as $flux) {
                        $textRun->addLink("FLUX".$flux->id, $flux->name, CartographyController::FancyLinkStyle, null, true);
                        if ($application->applicationSourceFluxes->last()!=$flux)
                            $textRun->addText(", ");
                    }
                    $textRun->addTextBreak(1); 
                    $textRun->addText("Destination : " );
                    foreach($application->applicationDestFluxes as $flux) {
                        $textRun->addLink("FLUX".$flux->id, $flux->name, CartographyController::FancyLinkStyle, null, true);
                        if ($application->applicationDestFluxes->last()!=$flux)
                            $textRun->addText(", ");
                    }

                    $textRun=$this->addTextRunRow($table, "Besoin de sécurité");
                    if ($application->security_need==1)
                        $textRun->addText("Public");
                    elseif ($application->security_need==2) 
                        $textRun->addText("Interne");
                    elseif ($application->security_need==3) 
                        $textRun->addText("Confidentiel");
                    elseif ($application->security_need==4) 
                        $textRun->addText("Secret");
                    else
                        $textRun->addText("-");

                    $this->addTextRow($table,"Exposition à l’externe",$application->external);

                    $textRun=$this->addTextRunRow($table,"Processus utilisant l’application");
                    foreach($application->processes as $process) {
                        $textRun->addLink("PROCESS".$process->id, $process->identifiant, CartographyController::FancyLinkStyle, null, true);
                        if ($application->processes->last()!=$process)
                            $textRun->addText(", ");
                    }

                    $textRun=$this->addTextRunRow($table,"Services applicatifs délivrés par l’application");
                    foreach($application->services as $service) {
                        $textRun->addLink("APPLICATIONSERVICE".$service->id, $service->name, CartographyController::FancyLinkStyle, null, true);
                        if ($application->services->last()!=$service)
                            $textRun->addText(", ");
                    }

                    $textRun=$this->addTextRunRow($table,"Bases de données utilisées");
                    foreach($application->databases as $database) {
                        $textRun->addLink("DATABASE".$database->id, $database->name, CartographyController::FancyLinkStyle, null, true);
                        if ($application->databases->last()!=$database)
                            $textRun->addText(", ");
                    }

                    $textRun=$this->addTextRunRow($table,"Bloc applicatif");
                    if ($application->application_block!=null)
                        $textRun->addLink("APPLICATIONBLOCK".$application->application_block_id, $application->application_block->name, CartographyController::FancyLinkStyle, null, true);

                    $textRun=$this->addTextRunRow($table,"Liste des serveurs logiques soutenant l’application");
                    foreach($application->logical_servers as $logical_server) {
                        $textRun->addLink("LOGICAL_SERVER".$logical_server->id, $logical_server->name, CartographyController::FancyLinkStyle, null, true);
                        if ($application->logical_servers->last()!=$logical_server)
                            $textRun->addText(", ");
                    }
                    
                    $section->addTextBreak(1); 
                } 
            }

            // =====================================
            if ($applicationServices->count()>0) { 
                $section->addTitle('Services applicatif', 2);
                $section->addText("Élément de découpage de l’application mis à disposition de l’utilisateur final dans le cadre de son travail. Un service applicatif peut, par exemple, être un service dans le nuage (Cloud)");
                $section->addTextBreak(1); 

                foreach($applicationServices as $applicationService) { 
                    $section->addBookmark("APPLICATIONSERVICE".$applicationService->id);                
                    $table=$this->addTable($section, $applicationService->name);
                    $this->addHTMLRow($table,"Description",$applicationService->description);

                    // Modules
                    $textRun=$this->addTextRunRow($table,"Liste des modules qui le composent");
                    foreach($applicationService->modules as $module) {
                        $textRun->addLink("APPLICATIONMODULE".$module->id, $module->name, CartographyController::FancyLinkStyle, null, true);
                        if ($applicationService->modules->last()!=$module)
                            $textRun->addText(", ");
                    }

                    // Flux
                    $textRun=$this->addTextRunRow($table,"Flux associés");
                    $textRun->addText("Source : ");
                    foreach($applicationService->serviceSourceFluxes as $flux) {
                        $textRun->addLink("FLUX".$flux->id, $flux->name, CartographyController::FancyLinkStyle, null, true);
                        if ($applicationService->serviceSourceFluxes->last()!=$flux)
                            $textRun->addText(", ");
                    }
                    $textRun->addTextBreak(1); 
                    $textRun->addText("Destination : ");
                    foreach($applicationService->serviceDestFluxes as $flux) {
                        $textRun->addLink("FLUX".$flux->id, $flux->name, CartographyController::FancyLinkStyle, null, true);
                        if ($applicationService->serviceDestFluxes->last()!=$flux)
                            $textRun->addText(", ");
                    }

                    $this->addTextRow($table,"Exposition à l’externe",$applicationService->external);

                    // Applications
                    $textRun=$this->addTextRunRow($table,"Applications qui utilisent ce service");
                    foreach($applicationService->servicesMApplications as $application) {
                        $textRun->addLink("APPLICATION".$application->id, $application->name, CartographyController::FancyLinkStyle, null, true);
                        if ($applicationService->servicesMApplications->last()!=$application)
                            $textRun->addText(", ");
                    }
                    $section->addTextBreak(1); 
                 }
             }

            // =====================================
            if ($applicationModules->count()>0) { 
                $section->addTitle('Modules applicatif', 2);
                $section->addText("Composant d’une application caractérisé par une cohérence fonctionnelle en matière d’informatique et une homogénéité technologique.");
                $section->addTextBreak(1); 

                foreach($applicationModules as $applicationModule) { 
                    $section->addBookmark("APPLICATIONMODULE".$applicationModule->id);                
                    $table=$this->addTable($section, $applicationModule->name);
                    $this->addHTMLRow($table,"Description",$applicationModule->description);

                    // Services
                    $textRun=$this->addTextRunRow($table,"Services qui utilisent ce module");
                    foreach($applicationModule->modulesApplicationServices as $service) {
                        $textRun->addLink("APPLICATIONSERVICE".$service->id, $service->name, CartographyController::FancyLinkStyle, null, true);
                        if ($applicationModule->modulesApplicationServices->last()!=$service)
                            $textRun->addText(", ");
                    }

                    // Flows
                    $textRun=$this->addTextRunRow($table,"Flux associés");
                    $textRun->addText("Source : ");
                    foreach($applicationModule->moduleSourceFluxes as $flux) {
                        $textRun->addLink("FLUX".$flux->id, $flux->name, CartographyController::FancyLinkStyle, null, true);
                        if ($applicationModule->moduleSourceFluxes->last()!=$flux)
                            $textRun->addText(", ");
                    }
                    $textRun->addTextBreak(1); 
                    $textRun->addText("Destination : ");
                    foreach($applicationModule->moduleDestFluxes as $flux) {
                        $textRun->addLink("FLUX".$flux->id, $flux->name, CartographyController::FancyLinkStyle, null, true);
                        if ($applicationModule->moduleDestFluxes->last()!=$flux)
                            $textRun->addText(", ");
                    }
                    $section->addTextBreak(1); 
                 }
             }

            // =====================================
            if ($applicationModules->count()>0) { 
                $section->addTitle('Bases de données', 2);
                $section->addText("Ensemble structuré et ordonné d’informations destinées à être exploitées informatiquement.");
                $section->addTextBreak(1); 

                foreach($databases as $database) { 
                    $section->addBookmark("DATABASE".$database->id);                
                    $table=$this->addTable($section, $database->name);
                    $this->addHTMLRow($table,"Description",$database->description);

                    // Services
                    $textRun=$this->addTextRunRow($table,"Entité(s) utilisatrice(s)");
                    foreach($database->entities as $entity) {
                        $textRun->addLink("ENTITY".$entity->id, $entity->name, CartographyController::FancyLinkStyle, null, true);
                        if ($database->entities->last()!=$entity)
                            $textRun->addText(", ");
                    }

                    // entity_resp
                    $textRun=$this->addTextRunRow($table,"Entité resposanble de l'exploitation");
                    if ($database->entity_resp->id!=null)
                        $textRun->addLink("ENTITY".$database->entity_resp->id, $database->entity_resp->name, CartographyController::FancyLinkStyle, null, true);

                    $this->addTextRow($table,"Responsable SSI",$database->responsible);
                    $this->addTextRow($table,"Type de technologie",$database->type);

                    // flows
                    $textRun=$this->addTextRunRow($table,"Flux associés");
                    $textRun->addText("Source : ");
                    foreach($database->databaseSourceFluxes as $flux) {
                        $textRun->addLink("FLUX".$flux->id, $flux->name, CartographyController::FancyLinkStyle, null, true);
                        if ($database->databaseSourceFluxes->last()!=$flux)
                            $textRun->addText(", ");
                    }
                    $textRun->addTextBreak(1); 
                    $textRun->addText("Destination : ");
                    foreach($database->databaseDestFluxes as $flux) {
                        $textRun->addLink("FLUX".$flux->id, $flux->name, CartographyController::FancyLinkStyle, null, true);
                        if ($database->databaseDestFluxes->last()!=$flux)
                            $textRun->addText(", ");
                    }

                    // Informations
                    $textRun=$this->addTextRunRow($table,"Informations contenues");
                    foreach($database->informations as $information) {
                        $textRun->addLink("INFORMATION".$information->id, $information->name, CartographyController::FancyLinkStyle, null, true);
                        if ($database->informations->last()!=$information)
                            $textRun->addText(", ");
                    }

                    $textRun=$this->addTextRunRow($table, "Besoin de sécurité");
                    if ($database->security_need==1)
                        $textRun->addText("Public");
                    elseif ($database->security_need==2) 
                        $textRun->addText("Interne");
                    elseif ($database->security_need==3) 
                        $textRun->addText("Confidentiel");
                    elseif ($database->security_need==4) 
                        $textRun->addText("Secret");
                    else
                        $textRun->addText("-");

                    $this->addTextRow($table,"Exposition à l’externe",$database->external);
       
                    $section->addTextBreak(1); 
                    }
                }

            // =====================================
            if ($fluxes->count()>0) { 
                $section->addTitle('Flux', 2);
                $section->addText("Echange d’informations entre un émetteur ou un récepteur (service applicatif, application ou acteur).");
                $section->addTextBreak(1); 

                foreach($fluxes as $flux) {
                    $section->addBookmark("FLUX".$flux->id);
                    $table=$this->addTable($section, $flux->name);
                    $this->addHTMLRow($table,"Description",$flux->description);

                    // source
                    if ($flux->application_source!=null) {
                        $textRun=$this->addTextRunRow($table,"Application Source");
                        $textRun->addLink("APPLICATION".$flux->application_source->id, $flux->application_source->name, CartographyController::FancyLinkStyle, null, true);
                    }

                    if ($flux->service_source!=null) {
                        $textRun=$this->addTextRunRow($table,"Service Source");
                        $textRun->addLink("APPLICATIONSERVICE".$flux->service_source->id, $flux->service_source->name, CartographyController::FancyLinkStyle, null, true);
                    }

                    if ($flux->module_source!=null) {
                        $textRun=$this->addTextRunRow($table,"Module Source");
                        $textRun->addLink("APPLICATIONMODULE".$flux->module_source->id, $flux->module_source->name, CartographyController::FancyLinkStyle, null, true);
                    }

                    if ($flux->database_source!=null) {
                        $textRun=$this->addTextRunRow($table,"Database Source");
                        $textRun->addLink("DATABASE".$flux->database_source->id, $flux->database_source->name, CartographyController::FancyLinkStyle, null, true);
                    }

                    // Dest
                    if ($flux->application_dest!=null) {
                        $textRun=$this->addTextRunRow($table,"Application Destinataire");
                        $textRun->addLink("APPLICATION".$flux->application_dest->id, $flux->application_dest->name, CartographyController::FancyLinkStyle, null, true);
                    }

                    if ($flux->service_dest!=null) {
                        $textRun=$this->addTextRunRow($table,"Service Destinataire");
                        $textRun->addLink("APPLICATIONSERVICE".$flux->service_dest->id, $flux->service_dest->name, CartographyController::FancyLinkStyle, null, true);
                    }

                    if ($flux->module_dest!=null) {
                        $textRun=$this->addTextRunRow($table,"Module Destinataire");
                        $textRun->addLink("APPLICATIONMODULE".$flux->module_dest->id, $flux->module_dest->name, CartographyController::FancyLinkStyle, null, true);
                    }

                    if ($flux->database_dest!=null) {
                        $textRun=$this->addTextRunRow($table,"Database Destinataire");
                        $textRun->addLink("DATABASE".$flux->database_dest->id, $flux->database_dest->name, CartographyController::FancyLinkStyle, null, true);
                    }

                    $section->addTextBreak(1); 
                } 
            }

        }

        // =====================
        // Administration
        // =====================
        if ($vues==null || in_array("4",$vues)) {
            $section->addTextBreak(2);
            $section->addTitle("Administration", 1);
            $section->addText("La vue de l’administration est un cas particulier de la vue des applications. Elle répertorie les périmètres et les niveaux de privilèges des administrateurs.");
            $section->addTextBreak(1);

            // get all data
            $zones = ZoneAdmin::All();
            $annuaires = Annuaire::All();
            $forests = ForestAd::All();
            $domains = DomaineAd::All();

            // Generate Graph
            $graph = "digraph  {";
            foreach($zones as $zone) {
                $graph .= " Z". $zone->id . "[label=\"". $zone->name ."\" shape=none labelloc=b width=1 height=1.8 image=\"" . public_path("/images/zoneadmin.png"). "\"]";
                foreach($zone->zoneAdminAnnuaires as $annuaire) 
                    $graph .= " Z". $zone->id . "->A" . $annuaire->id;
                foreach($zone->zoneAdminForestAds as $forest) 
                    $graph .= " Z" . $zone->id . "->F" . $forest->id;
                }
            foreach($annuaires as $annuaire) 
                $graph .= " A". $annuaire->id . "[label=\"" .$annuaire->name . "\" shape=none labelloc=b width=1 height=1.8 image=\"" . public_path("/images/annuaire.png") ."\"]";
            foreach($forests as $forest) { 
                $graph .= " F" . $forest->id . "[label=\"" . $forest->name . "\" shape=none labelloc=b width=1 height=1.8 image=\"" . public_path("/images/ldap.png") ."\"]";
                foreach($forest->domaines as $domain)  
                  $graph .= " F" . $forest->id . "->D" . $domain->id;
                }
            foreach($domains as $domain)
                $graph .= " D" . $domain->id . "[label=\"" . $domain->name . "\" shape=none labelloc=b width=1 height=1.8 image=\"" . public_path("/images/domain.png") . "\"]";
            $graph .= "}";

            // IMAGE
            $image_paths[] = $image_path=$this->generateGraphImage($graph);
            Html::addHtml($section, '<table style="width:100%"><tr><td><img src="'.$image_path.'" width="'. min(600,getimagesize ($image_path)[0]/2) . '"/></td></tr></table>');
            $section->addTextBreak(1);

            // =====================================
            if ($zones->count()>0) { 
                $section->addTitle('Zones', 2);
                $section->addText("Ensemble de ressources (personnes, données, équipements) sous la responsabilité d’un (ou plusieurs) administrateur(s).");
                $section->addTextBreak(1); 

                foreach($zones as $zone) {
                    $section->addBookmark("ZONE".$zone->id);
                    $table=$this->addTable($section, $zone->name);
                    $this->addHTMLRow($table,"Description",$zone->description);

                    // Annuaires
                    $textRun=$this->addTextRunRow($table,"Annuaires");
                    foreach($zone->zoneAdminAnnuaires as $annuaire) {
                        $textRun->addLink("ANNUAIRE".$annuaire->id, $annuaire->name, CartographyController::FancyLinkStyle, null, true);
                        if ($zone->zoneAdminAnnuaires->last()!=$annuaire)
                            $textRun->addText(", ");
                    }

                    // Forets
                    $textRun=$this->addTextRunRow($table,"Forêt Active Directory / Arborescence LDAP");
                    foreach($zone->zoneAdminForestAds as $forest) {
                        $textRun->addLink("FOREST".$forest->id, $forest->name, CartographyController::FancyLinkStyle, null, true);
                        if ($zone->zoneAdminForestAds->last()!=$forest)
                            $textRun->addText(", ");
                    }
                    $section->addTextBreak(1); 
                 }
             }

            // =====================================
            if ($annuaires->count()>0) { 
                $section->addTitle('Service d’annuaire d’administration', 2);
                $section->addText("Applicatif regroupant les données sur les utilisateurs ou équipements informatiques de l’entreprise et permettant leur administration.");
                $section->addTextBreak(1); 

                foreach($annuaires as $annuaire) {
                    $section->addBookmark("ANNUAIRE".$annuaire->id);
                    $table=$this->addTable($section, $annuaire->name);
                    $this->addHTMLRow($table,"Description",$annuaire->description);

                    $this->addTextRow($table,"Solution",$annuaire->solution);

                    // Zone d'administration
                    $textRun=$this->addTextRunRow($table,"Zone d'administration");
                    if ($annuaire->zone_admin!=null) 
                        $textRun->addLink("ZONE".$annuaire->zone_admin->id, $annuaire->zone_admin->name, CartographyController::FancyLinkStyle, null, true);
                       
                    $section->addTextBreak(1); 
                    }
                }

            // =====================================
            if ($forests->count()>0) { 
                $section->addTitle('Forêt Active Directory / Arborescence LDAP', 2);
                $section->addText("Regroupement organisé de domaines Active Directory/LDAP.");
                $section->addTextBreak(1); 

                foreach($forests as $forest) {
                    $section->addBookmark("FOREST".$forest->id);
                    $table=$this->addTable($section, $forest->name);
                    $this->addHTMLRow($table,"Description",$forest->description);

                    // Zone d'administration
                    $textRun=$this->addTextRunRow($table,"Zone d'administration");
                    if ($forest->zone_admin!=null) 
                        $textRun->addLink("ZONE".$forest->zone_admin->id, $forest->zone_admin->name, CartographyController::FancyLinkStyle, null, true);

                    // Domaines
                    $textRun=$this->addTextRunRow($table,"Domaines");
                    foreach($forest->domaines as $domain) {
                        $textRun->addLink("DOMAIN".$domain->id, $domain->name, CartographyController::FancyLinkStyle, null, true);
                        if ($forest->domaines->last()!=$domain)
                            $textRun->addText(", ");
                        }
                    $section->addTextBreak(1); 
                    }
                }

            // =====================================
            if ($domains->count()>0) { 
                $section->addTitle('Domaines', 2);
                $section->addText("Ensemble d’éléments (membres, ressources) régis par une même politique de sécurité.");
                $section->addTextBreak(1); 

                foreach($domains as $domain) { 
                    $section->addBookmark("DOMAIN".$domain->id);
                    $table=$this->addTable($section, $domain->name);
                    $this->addHTMLRow($table,"Description",$domain->description);

                    $this->addTextRow($table,"Nombre de controleurs de domaine",$domain->domain_ctrl_cnt);
                    $this->addTextRow($table,"Nombre de comptes utilisateurs rattachés",$domain->user_count);
                    $this->addTextRow($table,"Nombre de machines rattachées",$domain->machine_count);
                    $this->addTextRow($table,"Relations inter-domaines",$domain->relation_inter_domaine);

                    $this->addTextRow($table,"Forêt Active Directory / Arborescence LDAP",$domain->relation_inter_domaine);

                    // FOREST
                    $textRun=$this->addTextRunRow($table,"Domaines");
                    foreach($domain->domainesForestAds as $forest) {
                        $textRun->addLink("FOREST".$forest->id, $forest->name, CartographyController::FancyLinkStyle, null, true);
                        if ($domain->domainesForestAds->last()!=$forest)
                            $textRun->addText(", ");
                        }
                    $section->addTextBreak(1); 
                    }
                }
        }

        // =====================
        // Infrastructure logique
        // =====================
        if ($vues==null || in_array("5",$vues)) {
            $section->addTextBreak(2);
            $section->addTitle("Infrastructure logique", 1);
            $section->addText("La vue de l'infrastructure logique correspond à la répartition logique du réseau. Elle illustre le cloisonnement des réseaux et les liens logiques entre eux. En outre, elle répertorie les équipements réseau en charge du trafic.");
            $section->addTextBreak(1);

            // Get all data
            $networks = Network::All()->sortBy("name");
            $subnetworks = Subnetword::All()->sortBy("name");
            $gateways = Gateway::All()->sortBy("name");
            $externalConnectedEntities = ExternalConnectedEntity::All()->sortBy("name");
            $networkSwitches = NetworkSwitch::All()->sortBy("name");
            $routers = Router::All()->sortBy("name");
            $securityDevices = SecurityDevice::All()->sortBy("name");
            $dhcpServers = DhcpServer::All()->sortBy("name");
            $dnsservers = Dnsserver::All()->sortBy("name");
            $logicalServers = LogicalServer::All()->sortBy("name");

            // Generate Graph
            $graph = "digraph  {";
            foreach($networks as $network) { 
                $graph .= " NET" . $network->id . "[label=\"" . $network->name . "\" shape=none labelloc=b width=1 height=1.8 image=\"" . public_path("/images/cloud.png") . "\"]";
                foreach($network->subnetworks as $subnetwork) 
                    $graph .= " NET" . $network->id . "->SUBNET" . $subnetwork->id;
                }
            foreach($gateways as $gateway) {
                $graph .= " GATEWAY" . $gateway->id . "[label=\"" . $gateway->name . "\" shape=none labelloc=b width=1 height=1.8 image=\"" . public_path("/images/gateway.png") . "\"]";
                foreach($gateway->gatewaySubnetworks as $subnetwork) 
                    $graph .= " NET" . $subnetwork->id . "->GATEWAY" . $gateway->id;                
                }
            foreach($subnetworks as $subnetwork) 
                $graph .= " SUBNET" . $subnetwork->id . "[label=\"" . $subnetwork->name ."\" shape=none labelloc=b width=1 height=1.8 image=\"" . public_path("/images/network.png") . "\"]";
            foreach($externalConnectedEntities as $entity) {
                $graph .= " E" . $entity->id . "[label=\"" . $entity->name . "\" shape=none labelloc=b width=1 height=1.8 image=\"" . public_path("/images/entity.png") . "\"]";
                foreach($entity->connected_networks as $network)
                    $graph .= " NET" . $network->id . "->E" . $entity->id;                
                }
            $graph .= "}";
            
            // IMAGE
            $image_paths[] = $image_path=$this->generateGraphImage($graph);
            Html::addHtml($section, '<table style="width:100%"><tr><td><img src="'.$image_path.'" width="'. min(600,getimagesize ($image_path)[0]/2) . '"/></td></tr></table>');
            $section->addTextBreak(1);

            // =====================================
            if ($networks->count()>0) { 
                $section->addTitle('Réseaux', 2);
                $section->addText("Ensemble d’équipements reliés logiquement entre eux et qui échangent des informations.");
                $section->addTextBreak(1); 

                foreach($networks as $network) {
                    $section->addBookmark("NETWORK".$zone->id);
                    $table=$this->addTable($section, $network->name);
                    $this->addHTMLRow($table,"Description",$network->description);

                    $this->addTextRow($table,"Type de protocol",$network->protocol_type);
                    $this->addTextRow($table,"Responsable d'exploitation",$network->responsible);
                    $this->addTextRow($table,"Responsable SSI",$network->responsible_sec);

                    // subnetworks
                    $textRun=$this->addTextRunRow($table,"Sous-réseaux ratachés");
                    foreach($network->subnetworks as $subnetwork) {
                        $textRun->addLink("SUBNET".$subnetwork->id, $subnetwork->name, CartographyController::FancyLinkStyle, null, true);
                        if ($network->subnetworks->last()!=$subnetwork)
                            $textRun->addText(", ");
                        }

                    // entités externes connectées
                    $textRun=$this->addTextRunRow($table,"Entités externes connectées");
                    foreach($network->connectedNetworksExternalConnectedEntities as $entity) {
                        $textRun->addLink("ENTITY".$entity->id, $entity->name, CartographyController::FancyLinkStyle, null, true);
                        if ($network->connectedNetworksExternalConnectedEntities->last()!=$entity)
                            $textRun->addText(", ");
                        }
                    $section->addTextBreak(1); 
                    }
                }
                

            // =====================================
            if ($subnetworks->count()>0) { 
                $section->addTitle('Sous-Réseaux', 2);
                $section->addText("Subdivision logique d’un réseau de taille plus importante.");
                $section->addTextBreak(1); 

                foreach($subnetworks as $subnetwork) {
                    $section->addBookmark("SUBNET".$subnetwork->id);
                    $table=$this->addTable($section, $subnetwork->name);
                    $this->addHTMLRow($table,"Description",$subnetwork->description);

                    $this->addTextRow($table,"Adresse/Masque",$subnetwork->address);
                    // gateway
                    $textRun=$this->addTextRunRow($table,"Passerelle");
                    if ($subnetwork->gateway!=null) 
                        $textRun->addLink("GATEWAY".$subnetwork->gateway->id, $subnetwork->gateway->name, CartographyController::FancyLinkStyle, null, true);
                    $this->addTextRow($table,"Plage d’adresses IP",$subnetwork->ip_range);
                    $this->addTextRow($table,"Méthode d’attribution des IP",$subnetwork->ip_allocation_type);
                    $this->addTextRow($table,"Responsable d’exploitation",$subnetwork->responsible_exp);
                    $this->addTextRow($table,"DMZ ou non",$subnetwork->dmz);

                    // subnetworks
                    $textRun=$this->addTextRunRow($table,"Sous-réseaux connectés");
                    if ($subnetwork->connected_subnets!=null) {
                        $textRun->addLink("SUBNET".$subnetwork->connected_subnets->id, $subnetwork->connected_subnets->name, CartographyController::FancyLinkStyle, null, true);
                        }

                    $this->addTextRow($table,"Possibilité d’accès sans ﬁl",$subnetwork->wifi);

                    $section->addTextBreak(1); 
                    }
                }

            // =====================================
            if ($gateways->count()>0) { 
                $section->addTitle('Passerelle d’entrée depuis l’extérieur', 2);
                $section->addText("Composants permettant de relier un réseau local avec l’extérieur");
                $section->addTextBreak(1); 

                foreach($gateways as $gateway) {
                    $section->addBookmark("GATEWAY".$gateway->id);
                    $table=$this->addTable($section, $gateway->name);
                    $this->addHTMLRow($table,"Caractéristiques techniques",$gateway->description);

                    $this->addTextRow($table,"Type d'authentification",$gateway->authentication);
                    $this->addTextRow($table,"IP publique et privée",$gateway->ip);

                    // Réseau ratachés
                    $textRun=$this->addTextRunRow($table,"Réseaux ratachés");
                    foreach($gateway->gatewaySubnetworks as $subnetwork) {
                        $textRun->addLink("SUBNET".$subnetwork->id, $subnetwork->name, CartographyController::FancyLinkStyle, null, true);
                        if ($gateway->gatewaySubnetwords->last()!=$subnetwork)
                            $textRun->addText(", ");
                        }

                    $section->addTextBreak(1); 
                    }
                }

            // =====================================
            if ($externalConnectedEntities->count()>0) { 
                $section->addTitle('Entités extérieurs connectées', 2);
                $section->addText("Entités externes connectées au réseau.");
                $section->addTextBreak(1); 

                foreach($externalConnectedEntities as $entity) {
                    $section->addBookmark("EXTENTITY".$entity->id);
                    $table=$this->addTable($section, $entity->name);

                    $this->addTextRow($table,"Responsable SSI",$entity->responsible_sec);
                    $this->addTextRow($table,"Contacts SI",$entity->contacts);

                    $textRun=$this->addTextRunRow($table,"Réseaux connectés");
                    foreach($entity->connected_networks as $network) {
                        $textRun->addLink("NETWORK".$network->id, $network->name, CartographyController::FancyLinkStyle, null, true);
                        if ($entity->connected_networks->last()!=$network)
                            $textRun->addText(", ");
                        }
                    $section->addTextBreak(1); 
                    }
                }

            // =====================================
            if ($logicalServers->count()>0) { 
                $section->addTitle('Serveurs logiques', 2);
                $section->addText("Découpage logique d’un serveur physique.");
                $section->addTextBreak(1); 

                foreach($logicalServers as $logicalServer) {
                    $section->addBookmark("LOGICAL_SERVER".$logicalServer->id);
                    $table=$this->addTable($section, $logicalServer->name);
                    $this->addHTMLRow($table,"Description",$logicalServer->description);

                    $this->addTextRow($table,"Operating System",$logicalServer->operating_system);
                    $this->addTextRow($table,"Adresse IP",$logicalServer->address_ip);
                    $this->addTextRow($table,"CPU",$logicalServer->cpu);
                    $this->addTextRow($table,"Mémoire",$logicalServer->memory);
                    $this->addTextRow($table,"Disque",$logicalServer->disk);
                    $this->addTextRow($table,"Services réseau",$logicalServer->net_services);

                    $this->addHTMLRow($table,"Configuration",$logicalServer->configuration);

                    // APPLICATIONS
                    $textRun=$this->addTextRunRow($table,"Applications");
                    foreach($logicalServer->applications as $application) {
                        $textRun->addLink("APPLICATION".$application->id, $application->name, CartographyController::FancyLinkStyle, null, true);
                        if ($logicalServer->applications->last()!=$application)
                            $textRun->addText(", ");
                        }

                    // Physical server
                    $textRun=$this->addTextRunRow($table,"Serveurs physiques");
                    foreach($logicalServer->servers as $server) {
                        $textRun->addLink("PSERVER".$server->id, $server->name, CartographyController::FancyLinkStyle, null, true);
                        if ($logicalServer->servers->last()!=$server)
                            $textRun->addText(", ");
                        }

                    $section->addTextBreak(1); 
                    }
                }
            }

        // =====================
        // Infrastructure physique
        // =====================
        if ($vues==null || in_array("6",$vues)) {
            $section->addTextBreak(2);
            $section->addTitle("Infrastructure physique", 1);
            $section->addText("La vue des infrastructures physiques décrit les équipements physiques qui composent le système d’information ou qui sont utilisés par celui-ci. Cette vue correspond à la répartition géographique des équipements réseaux au sein des différents sites.");
            $section->addTextBreak(1);

            // Get all data
            $sites=Site::All()->sortBy("name");
            $buildings = Building::All()->sortBy("name");            
            $bays = Bay::All()->sortBy("name");
            $physicalServers = PhysicalServer::All()->sortBy("name");
            $workstations = Workstation::All()->sortBy("name");
            $storageDevices = StorageDevice::All()->sortBy("name");
            $peripherals = Peripheral::All()->sortBy("name");
            $phones = Phone::All()->sortBy("name");
            $physicalSwitches = PhysicalSwitch::All()->sortBy("name");
            $physicalRouters = PhysicalRouter::All()->sortBy("name");

            // Generate Graph
            $graph = "digraph  {";
            foreach($sites as $site) {
                $graph .= " S" . $site->id . "[label=\"". $site->name . "\" shape=none labelloc=b width=1 height=1.8 image=\"" . public_path("/images/site.png") ."\"]";
            }            
            foreach($buildings as $building) {
                $graph .= " B". $building->id . "[label=\"" . $building->name . "\" shape=none labelloc=b width=1 height=1.8 image=\"" . public_path("/images/building.png") . "\"]";
                $graph .= " S". $building->site_id . "->B" . $building->id;
                foreach($building->roomBays as $bay) {
                    $graph .= " B". $building->id . "->BAY" . $bay->id;
                }
            }                
            foreach($bays as $bay) {
                $graph .= " BAY" . $bay->id . "[label=\"" . $bay->name . "\" shape=none labelloc=b width=1 height=1.8 image=\"" . public_path("/images/bay.png") . "\"]";
            }
            foreach($physicalServers as $pServer) {
                $graph .= " PSERVER" . $pServer->id . "[label=\"" . $pServer->name . "\" shape=none labelloc=b width=1 height=1.8 image=\"" . public_path("/images/server.png") . "\"]";
                if ($pServer->bay!=null) 
                     $graph .= " BAY" . $pServer->bay->id . "->PSERVER" . $pServer->id;
                elseif ($pServer->building!=null) 
                     $graph .= " B" . $pServer->building->id . "->PSERVER" . $pServer->id;
                elseif ($pServer->site!=null)
                     $graph .= " S" . $pServer->site->id ."->PSERVER" . $pServer->id;                
            }
            foreach($workstations as $workstation) {
                $graph .= " W" . $workstation->id . "[label=\"" .$workstation->name . "\" shape=none labelloc=b width=1 height=1.8 image=\"" . public_path("/images/workstation.png") ."\"]";
                if ($workstation->building!=null)
                     $graph .= " B" . $workstation->building->id . "->W" . $workstation->id;
                elseif ($workstation->site!=null)
                     $graph .= " S" . $workstation->building->id . "->W" . $workstation->id;
                }            
            foreach($storageDevices as $storageDevice) {
                $graph .= " SD" . $storageDevice->id ."[label=\"" . $storageDevice->name . "\" shape=none labelloc=b width=1 height=1.8 image=\"" . public_path("/images/storage.png") . "\"]";
                if ($storageDevice->bay!=null)
                     $graph .= " BAY" . $storageDevice->bay->id . "->SD" . $storageDevice->id;
                elseif ($storageDevice->building!=null)
                     $graph .= " B" . $storageDevice->building->id . "->SD" . $storageDevice->id;
                elseif ($storageDevice->site!=null)
                     $graph .= " S" . $storageDevice->site->id . "->SD" . $storageDevice->id;
                }
            foreach($peripherals as $peripheral) {
                $graph .= " PER" . $peripheral->id . "[label=\"" . $peripheral->name ."\" shape=none labelloc=b width=1 height=1.8 image=\"" . public_path("/images/peripheral.png") ."\"]";
                if ($peripheral->bay!=null)
                     $graph .= " BAY" . $peripheral->bay->id ."->PER" . $peripheral->id;
                elseif ($peripheral->building!=null)
                     $graph .= " B". $peripheral->building->id . "->PER" . $peripheral->id;
                elseif ($peripheral->site!=null)
                     $graph .= " S" . $peripheral->site->id . "->PER" . $peripheral->id;
                }
            foreach($phones as $phone) {
                $graph .= " PHONE" . $phone->id . "[label=\"" . $phone->name ."\" shape=none labelloc=b width=1 height=1.8 image=\"" . public_path("/images/phone.png") ."\"]";
                if ($phone->building!=null)
                     $graph .= " B" . $phone->building->id ."->PHONE" . $phone->id;
                elseif ($phone->site!=null)
                     $graph .= " S" . $phone->site->id . "->PHONE" . $phone->id;                
                }
            foreach($physicalSwitches as $switch) {
                $graph .= " SWITCH" . $switch->id . "[label=\"" . $switch->name . "\" shape=none labelloc=b width=1 height=1.8 image=\"" . public_path("/images/switch.png") ."\"]";
                if ($switch->bay!=null)
                     $graph .= " BAY" . $switch->bay->id . "->SWITCH" . $switch->id;
                elseif ($switch->building!=null)
                     $graph .= " B" . $switch->building->id . "->SWITCH". $switch->id;
                elseif ($switch->site!=null)
                     $graph .= " S" . $switch->site->id . "->SWITCH" . $switch->id;
                }
            foreach($physicalRouters as $router) { 
                $graph .= " ROUTER" . $router->id . "[label=\"" . $router->name . "\" shape=none labelloc=b width=1 height=1.8 image=\"" . public_path("/images/router.png") . "\"]";
                if ($router->bay!=null)
                     $graph .= " BAY" . $router->bay->id . "->ROUTER" . $router->id;
                elseif ($router->building!=null)
                     $graph .= " B" . $router->building->id . "->ROUTER" . $router->id;
                elseif ($router->site!=null)
                     $graph .= " S" . $router->site->id . "->ROUTER" . $router->id;
                }
            $graph .= "}";

            // IMAGE
            $image_paths[] = $image_path=$this->generateGraphImage($graph);
            Html::addHtml($section, '<table style="width:100%"><tr><td><img src="'.$image_path.'" width="'. min(600,getimagesize ($image_path)[0]/2) . '"/></td></tr></table>');
            $section->addTextBreak(1);

            // =====================================
            if ($sites->count()>0) { 
                $section->addTitle('Sites', 2);
                $section->addText("Emplacement géographique rassemblant un ensemble de personnes et/ou de ressources.");
                $section->addTextBreak(1); 

                foreach($sites as $site) {
                    $section->addBookmark("SITE".$site->id);
                    $table=$this->addTable($section, $site->name);
                    $this->addHTMLRow($table,"Description",$site->description);

                    // Buildings
                    $textRun=$this->addTextRunRow($table,"Buildings");
                    foreach($site->siteBuildings as $building) {
                        $textRun->addLink("BUILDING".$building->id, $building->name, CartographyController::FancyLinkStyle, null, true);
                        if ($site->siteBuildings->last()!=$building)
                            $textRun->addText(", ");
                        }

                    $section->addTextBreak(1); 
                    }
                }            

            // =====================================
            if ($buildings->count()>0) { 
                $section->addTitle('Bâtiments / Salles', 2);
                $section->addText("Localisation des personnes ou ressources à l’intérieur d’un site.");
                $section->addTextBreak(1); 

                foreach($buildings as $building) {
                    $section->addBookmark("BUILDING".$building->id);
                    $table=$this->addTable($section, $building->name);
                    $this->addHTMLRow($table,"Description",$building->description);

                    // Baies
                    $textRun=$this->addTextRunRow($table,"Baies");
                    foreach($building->roomBays as $bay) {
                        $textRun->addLink("BAY".$bay->id, $bay->name, CartographyController::FancyLinkStyle, null, true);
                        if ($building->roomBays->last()!=$bay)
                            $textRun->addText(", ");
                        }

                    $section->addTextBreak(1); 
                    }
                }            

            // =====================================
            if ($buildings->count()>0) { 
                $section->addTitle('Baies', 2);
                $section->addText("Armoire technique rassemblant des équipements de réseau informatique ou de téléphonie.");
                $section->addTextBreak(1); 

                foreach($bays as $bay) {
                    $section->addBookmark("BAY".$bay->id);
                    $table=$this->addTable($section, $bay->name);
                    $this->addHTMLRow($table,"Description",$bay->description);

                    // Serveurs
                    $textRun=$this->addTextRunRow($table,"Serveurs physique");
                    foreach($bay->bayPhysicalServers as $physicalServer) {
                        $textRun->addLink("PSERVER".$physicalServer->id, $physicalServer->name, CartographyController::FancyLinkStyle, null, true);
                        if ($bay->bayPhysicalServers->last()!=$physicalServer)
                            $textRun->addText(", ");
                        }

                    $section->addTextBreak(1); 
                    }
                }            

            // =====================================
            if ($physicalServers->count()>0) { 
                $section->addTitle('Serveurs physiques', 2);
                $section->addText("Machine physique exécutant un ensemble de services informatiques.");
                $section->addTextBreak(1); 

                foreach($physicalServers as $server) {
                    $section->addBookmark("PSERVER".$server->id);
                    $table=$this->addTable($section, $server->name);
                    $this->addHTMLRow($table,"Description",$server->description);
                    $this->addHTMLRow($table,"Configuration",$server->configuration);

                    if ($server->site!=null) {
                        $textRun=$this->addTextRunRow($table,"Site");
                        $textRun->addLink("SITE".$server->site->id, $server->site->name, CartographyController::FancyLinkStyle, null, true);
                        }

                    if ($server->building!=null) {
                        $textRun=$this->addTextRunRow($table,"Building / Salle");
                        $textRun->addLink("BUILDING".$server->building->id, $server->building->name, CartographyController::FancyLinkStyle, null, true);
                        }

                    if ($server->bay!=null) {
                        $textRun=$this->addTextRunRow($table,"Baie");
                        $textRun->addLink("BAY".$server->bay->id, $server->bay->name, CartographyController::FancyLinkStyle, null, true);
                        }

                    $this->addTextRow($table,"Responsable",$server->responsible);

                    // Serveurs logiques
                    $textRun=$this->addTextRunRow($table,"Serveurs logiques");
                    foreach($server->serversLogicalServers as $logicalServer) {
                        $textRun->addLink("PSERVER".$logicalServer->id, $logicalServer->name, CartographyController::FancyLinkStyle, null, true);
                        if ($server->serversLogicalServers->last()!=$logicalServer)
                            $textRun->addText(", ");
                        }

                    $section->addTextBreak(1); 
                    }
                }            

            // =====================================
            if ($physicalServers->count()>0) { 
                $section->addTitle('Postes de travail', 2);
                $section->addText("Machine physique permettant à un utilisateur d’accéder au système d’information.");
                $section->addTextBreak(1); 

                foreach($workstations as $workstation) {
                    $section->addBookmark("WORKSTATION".$workstation->id);
                    $table=$this->addTable($section, $workstation->name);
                    $this->addHTMLRow($table,"Description",$workstation->description);
                    $this->addHTMLRow($table,"Configuration",$workstation->configuration);

                    if ($workstation->site!=null) {
                        $textRun=$this->addTextRunRow($table,"Site");
                        $textRun->addLink("SITE".$workstation->site->id, $workstation->site->name, CartographyController::FancyLinkStyle, null, true);
                        }

                    if ($workstation->building!=null) {
                        $textRun=$this->addTextRunRow($table,"Building / Salle");
                        $textRun->addLink("BUILDING".$workstation->building->id, $workstation->building->name, CartographyController::FancyLinkStyle, null, true);
                        }


                    $section->addTextBreak(1); 
                    }
                }            

            // =====================================
            if ($storageDevices->count()>0) { 
                $section->addTitle('Infrastructure de stockage', 2);
                $section->addText("Support physique ou réseau de stockage de données : serveur de stockage en réseau (NAS), réseau de stockage (SAN), disque dur…");
                $section->addTextBreak(1); 

                foreach($storageDevices as $storageDevice) {
                    $section->addBookmark("STORAGEDEVICE".$storageDevice->id);
                    $table=$this->addTable($section, $storageDevice->name);
                    $this->addHTMLRow($table,"Description",$storageDevice->description);

                    if ($storageDevice->site!=null) {
                        $textRun=$this->addTextRunRow($table,"Site");
                        $textRun->addLink("SITE".$storageDevice->site->id, $storageDevice->site->name, CartographyController::FancyLinkStyle, null, true);
                        }

                    if ($storageDevice->building!=null) {
                        $textRun=$this->addTextRunRow($table,"Building / Salle");
                        $textRun->addLink("BUILDING".$storageDevice->building->id, $storageDevice->building->name, CartographyController::FancyLinkStyle, null, true);
                        }

                    if ($storageDevice->bay!=null) {
                        $textRun=$this->addTextRunRow($table,"Baie");
                        $textRun->addLink("BAY".$storageDevice->bay->id, $storageDevice->bay->name, CartographyController::FancyLinkStyle, null, true);
                        }

                    $section->addTextBreak(1); 
                    }
                }            

            // =====================================
            if ($storageDevices->count()>0) { 
                $section->addTitle('Périphériques', 2);
                $section->addText("Composant physique connecté à un poste de travail aﬁn d’ajouter de nouvelles fonctionnalités (ex. : clavier, souris, imprimante, scanner, etc.).");
                $section->addTextBreak(1); 

                foreach($peripherals as $peripheral) {
                    $section->addBookmark("PERIPHERAL".$peripheral->id);
                    $table=$this->addTable($section, $peripheral->name);
                    $this->addHTMLRow($table,"Description",$peripheral->description);

                    $this->addTextRow($table,"Type",$peripheral->type);
                    $this->addTextRow($table,"Responsable",$peripheral->responsible);

                    if ($peripheral->site!=null) {
                        $textRun=$this->addTextRunRow($table,"Site");
                        $textRun->addLink("SITE".$peripheral->site->id, $peripheral->site->name, CartographyController::FancyLinkStyle, null, true);
                        }

                    if ($peripheral->building!=null) {
                        $textRun=$this->addTextRunRow($table,"Building / Salle");
                        $textRun->addLink("BUILDING".$peripheral->building->id, $peripheral->building->name, CartographyController::FancyLinkStyle, null, true);
                        }

                    if ($peripheral->bay!=null) {
                        $textRun=$this->addTextRunRow($table,"Baie");
                        $textRun->addLink("BAY".$peripheral->bay->id, $peripheral->bay->name, CartographyController::FancyLinkStyle, null, true);
                        }

                    $section->addTextBreak(1); 
                    }
                }            

            // =====================================
            if ($storageDevices->count()>0) { 
                $section->addTitle('Téléphones', 2);
                $section->addText("Téléphone ﬁxe ou portable appartenant à l’organisation.");
                $section->addTextBreak(1); 

                foreach($phones as $phone) {
                    $section->addBookmark("PHONE".$phone->id);
                    $table=$this->addTable($section, $phone->name);
                    $this->addHTMLRow($table,"Description",$phone->description);

                    $this->addTextRow($table,"Type",$phone->type);

                    if ($phone->site!=null) {
                        $textRun=$this->addTextRunRow($table,"Site");
                        $textRun->addLink("SITE".$phone->site->id, $phone->site->name, CartographyController::FancyLinkStyle, null, true);
                        }

                    if ($phone->building!=null) {
                        $textRun=$this->addTextRunRow($table,"Building / Salle");
                        $textRun->addLink("BUILDING".$phone->building->id, $phone->building->name, CartographyController::FancyLinkStyle, null, true);
                        }

                    $section->addTextBreak(1); 
                    }
                }            

            // =====================================
            if ($physicalSwitches->count()>0) { 
                $section->addTitle('Commutateurs', 2);
                $section->addText("Composant gérant les connexions entre les différents serveurs au sein d’un réseau.");
                $section->addTextBreak(1); 

                foreach($physicalSwitches as $switch) {
                    $section->addBookmark("SWITCH".$switch->id);
                    $table=$this->addTable($section, $switch->name);
                    $this->addHTMLRow($table,"Description",$switch->description);

                    $this->addTextRow($table,"Type",$switch->type);


                    if ($switch->site!=null) {
                        $textRun=$this->addTextRunRow($table,"Site");
                        $textRun->addLink("SITE".$switch->site->id, $switch->site->name, CartographyController::FancyLinkStyle, null, true);
                        }

                    if ($switch->building!=null) {
                        $textRun=$this->addTextRunRow($table,"Building / Salle");
                        $textRun->addLink("BUILDING".$switch->building->id, $switch->building->name, CartographyController::FancyLinkStyle, null, true);
                        }

                    if ($switch->bay!=null) {
                        $textRun=$this->addTextRunRow($table,"Baie");
                        $textRun->addLink("BAY".$switch->bay->id, $switch->bay->name, CartographyController::FancyLinkStyle, null, true);
                        }

                    $section->addTextBreak(1); 
                    }
                }            

            // =====================================
            if ($physicalSwitches->count()>0) { 
                $section->addTitle('Routeur Physique', 2);
                $section->addText("Composant gérant les connexions entre différents réseaux.");
                $section->addTextBreak(1); 

                foreach($physicalRouters as $router) {
                    $section->addBookmark("ROUTER".$router->id);
                    $table=$this->addTable($section, $router->name);
                    $this->addHTMLRow($table,"Description",$router->description);

                    $this->addTextRow($table,"Type",$router->type);

                    if ($router->site!=null) {
                        $textRun=$this->addTextRunRow($table,"Site");
                        $textRun->addLink("SITE".$router->site->id, $router->site->name, CartographyController::FancyLinkStyle, null, true);
                        }

                    if ($router->building!=null) {
                        $textRun=$this->addTextRunRow($table,"Building / Salle");
                        $textRun->addLink("BUILDING".$router->building->id, $router->building->name, CartographyController::FancyLinkStyle, null, true);
                        }

                    if ($router->bay!=null) {
                        $textRun=$this->addTextRunRow($table,"Baie");
                        $textRun->addLink("BAY".$router->bay->id, $router->bay->name, CartographyController::FancyLinkStyle, null, true);
                        }

                    $section->addTextBreak(1); 
                    }
                }
            }

        // Finename
        $filepath=storage_path('app/reports/cartographie-'. Carbon::today()->format("Ymd") .'.docx');

        // Saving the document as Word2007 file.
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($filepath);

        // unlink the images
        foreach ($image_paths as $path) {
            unlink($path); 
        }

        // return
        return response()->download($filepath);       
    }

    // Generate the image of the graph from a dot notation using GraphViz
    private function generateGraphImage(String $graph) {

        // Save it to a file
        $dot_path = tempnam("/tmp","dot");
        $dot_file = fopen($dot_path, 'w');
        fwrite($dot_file, $graph);
        fclose($dot_file);

        // create image file
        $png_path = tempnam("/tmp","png");

        // dot -Tpng ./test.dot -otest.png
        shell_exec ("/usr/bin/dot -Tpng ".$dot_path." -o".$png_path);

        // delete graph file
        unlink($dot_path);

        // return file path (do not forget to delete after...)
        return $png_path;
    }

}

