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
    const FancyTableTitleStyle=array("bold"=>true, 'color' => '000000');
    const FancyLeftTableCellStyle=array("bold"=>true, 'color' => '000000');
    const FancyRightTableCellStyle=array("bold"=>false, 'color' => '000000');
    const FancyLinkStyle=array('color' => '006699');

    private static function addTable(Section $section, String $title=null) {
        $table = $section->addTable(
                array('borderSize' => 2, 'borderColor' => '006699', 'cellMargin' => 80,
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER, 'cellSpacing' => 1)
                );
        $table->addRow();
        $table->addCell(8000,array('gridSpan' => 2))
            ->addText($title,CartographyController::FancyTableTitleStyle,array('spaceAfter' => 0));
        return $table;
    }

    private static function addTextRow(Table $table, String $title, String $value=null) {
        $table->addRow();
        $table->addCell(2000)->addText($title,CartographyController::FancyLeftTableCellStyle);
        $table->addCell(6000)->addText($value);
    }

    private static function addHTMLRow(Table $table, String $title, String $value=null) { 
        $table->addRow();
        $table->addCell(2000)->addText($title, CartographyController::FancyLeftTableCellStyle);
        \PhpOffice\PhpWord\Shared\Html::addHtml($table->addCell(6000), str_replace("<br>", "<br/>", $value));
    }

    private static function addTextRunRow(Table $table, String $title) { 
        $table->addRow();
        $table->addCell(2000)->addText($title,CartographyController::FancyLeftTableCellStyle,array('spaceAfter' => 0));
        $cell=$table->addCell(6000);
        return $cell->addTextRun(CartographyController::FancyRightTableCellStyle,array('spaceAfter' => 0));
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
            Html::addHtml($section, '<table style="width:100%"><tr><td><img src="'.$image_path.'" width="600"/></td></tr></table>');
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
                    $textRun->addLink("PROCESS".$process->id, $process->identifiant, CartographyController::FancyLinkStyle, null, true);
                    if ($entity->entitiesProcesses->last() != $process)  
                        $textRun->addText(", ");
                    }
                $section->addTextBreak(1);
            }
                      
            // ===============================
            // $section->addTextBreak(2);
            $section->addTitle("Relations", 2);
            $section->addText("Lien entre deux entités ou systèmes.");
            $section->addTextBreak(1);

            // Loop on relations
            foreach ($relations as $relation) {
                $section->addBookmark("RELATION".$relation->id);
                $table = $this->addTable($section,$relation->name);
                $this->addHTMLRow($table,"Description",$relation->description);
                $this->addTextRow($table,"Type",$relation->type);

                $table->addRow();
                $table->addCell(2000)->addText("Importance",CartographyController::FancyRightTableCellStyle);
                if ($relation->inportance==1) 
                    $table->addCell(6000)->addText('Faible');
                elseif ($relation->inportance==2)
                    $table->addCell(6000)->addText('Moyen');
                elseif ($relation->inportance==3)
                    $table->addCell(6000)->addText('Fort');
                elseif ($relation->inportance==4)
                    $table->addCell(6000)->addText('Critique');
                else
                    $table->addCell(6000)->addText("");

                $textRun=$this->addTextRunRow($table,"Lien");
                $textRun->addLink('ENTITY'.$relation->source_id, $entities->find($relation->source_id)->name, CartographyController::FancyLinkStyle, null, true);
                $textRun->addText(" -> ");
                $textRun->addLink('ENTITY'.$relation->destination_id, $entities->find($relation->destination_id)->name, CartographyController::FancyLinkStyle, null, true);
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
            $processes = Process::All()->sortBy("name");
            $activities = Activity::All()->sortBy("name");
            $operations = Operation::All()->sortBy("name");
            $tasks = Task::All()->sortBy("name");
            $actors = Actor::All()->sortBy("name");
            $informations = Information::All()->sortBy("name");

            // Generate Graph
            $graph = "digraph  {";

            foreach($macroProcessuses as $macroProcess) 
                $graph .= " MP" . $macroProcess->id . " [label=\"". $macroProcess->name . "\" shape=none labelloc=b width=1 height=1.8 image=\"".public_path("/images/macroprocess.png")."\"]";
            
            foreach($processes as $process) {
                $graph .= " P".$process->id . " [label=\"" . $process->identifiant . "\" shape=none labelloc=b width=1 height=1.8 image=\"".public_path("/images/process.png")."\"]";
                foreach($process->activities as $activity)
                    $graph .= " P".$process->id . "->A". $activity->id;
                foreach($process->processInformation as $information) {
                    $graph .= " P". $process->id ."->I". $information->id;
                    if ($process->macroprocess_id!=null)
                        $graph.=" MP" . $process->macroprocess_id ."-> P".$process->id;
                    }    
            }
            foreach($activities as $activity) {
                $graph .= " A" . $activity->id ." [label=\"". $activity->name ."\" shape=none labelloc=b width=1 height=1.8 image=\"".public_path("/images/activity.png")."\"]";
                foreach($activity->operations as $operation)
                    $graph .= " A". $activity->id ."->O".$operation->id;
                }
            foreach($operations as $operation) {
                $graph .= " O". $operation->id ." [label=\"". $operation->name ."\" shape=none labelloc=b width=1 height=1.8 image=\"".public_path("/images/operation.png")."\"]";
                foreach($operation->tasks as $task)
                    $graph .= " O" . $operation->id . "->T". $task->id;                
                foreach($operation->actors as $actor)
                    $graph .= " O". $operation->id . "->ACT". $actor->id;
                }
            foreach($tasks as $task)
                $graph .= " T". $task->id . " [label=\"". $task->nom . "\" shape=none labelloc=b width=1 height=1.8 image=\"". public_path("/images/task.png")."\"]";
            foreach($actors as $actor)
                $graph .= " ACT". $actor->id . " [label=\"". $actor->name . "\" shape=none labelloc=b width=1 height=1.8 image=\"".public_path("/images/actor.png")."\"]";
            foreach($informations as $information)
                $graph .= " I". $information->id . " [label=\"" . $information->name . "\" shape=none labelloc=b width=1 height=1.8 image=\"".public_path("/images/information.png")."\"]";
            $graph .= "}";


            // IMAGE
            $image_paths[] = $image_path=$this->generateGraphImage($graph);
            Html::addHtml($section, '<table style="width:100%"><tr><td><img src="'.$image_path.'" width="600"/></td></tr></table>');
            $section->addTextBreak(1);

            // =====================================
            $section->addTitle('Macro-processus', 2);
            $section->addText("Les maro-processus représentent des ensembles de processus.");
            $section->addTextBreak(1);

            // Loop on relations
            foreach($macroProcessuses as $macroProcess) {
                $section->addBookmark("MACROPROCESS".$macroProcess->id);
                $table = $this->addTable($section, $macroProcess->name);
                $this->addHTMLRow($table, "Description", $macroProcess->description);
                $this->addHTMLRow($table, "Éléments entrants et sortants",$macroProcess->io_elements);
                $this->addTextRow($table, "Besoin de sécurité",$macroProcess->security_need);
                $this->addTextRow($table, "Propritétaire",$macroProcess->owner);                
                $textRun=$this->addTextRunRow($table, "Processus");
                foreach($macroProcess->processes as $process) {
                    $textRun->addLink("PROCESS".$process->id, $process->identifiant, CartographyController::FancyLinkStyle, null, true);
                    if ($macroProcess->processes->last() != $process)  
                        $textRun->addText(", ");
                    }
                $section->addTextBreak(1);
                }

            // =====================================
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
                    $textRun->addLink("ACTIVITY".$activity->id, $activity->name, CartographyController::FancyLinkStyle, null, true);
                    if ($process->activities->last() != $activity)  
                        $textRun->addText(", ");
                    }
                $textRun=$this->addTextRunRow($table,"Entités associées");
                foreach($process->entities as $entity) {
                    $textRun->addLink("ENTITY".$entity->id, $entity->name, CartographyController::FancyLinkStyle, null, true);
                    if ($process->entities->last() != $entity)  
                        $textRun->addText(", ");
                    }
                $textRun=$this->addTextRunRow($table,"Applications qui le soutiennent");
                foreach($process->processesMApplications as $application) {
                    $textRun->addLink("APPLICATION".$application->id, $application->name, CartographyController::FancyLinkStyle, null, true);
                    if ($process->processesMApplications->last() != $application)  
                        $textRun->addText(", ");
                    }
                $this->addTextRow($table,"Besoin de scurité",$process->security_need);
                $this->addTextRow($table,"Propriétaire",$process->owner);
                $section->addTextBreak(1);
                }

            // =====================================
            $section->addTitle('Opérations', 2);
            $section->addText("Étape d’une procédure correspondant à l’intervention d’un acteur dans le cadre d’une activité.");
            $section->addTextBreak(1); 
        }
        
        // =====================
        // APPLICATIONS
        // =====================
        if ($vues==null || in_array("3",$vues)) {
            $section->addTextBreak(2);
            $section->addTitle("Applications", 1);
            $section->addText("La vue des applications permet de décrire une partie de ce qui est classiquement appelé le « système informatique ». Cette vue décrit les solutions technologiques qui supportent les processus métiers, principalement les applications.");
            $section->addTextBreak(1);

        }

        // <option value="4">Administration</option>
        if ($vues==null || in_array("4",$vues)) {
            $section->addTextBreak(2);
            $section->addTitle("Administration", 1);
        }

        // <option value="5">Infrastructure physique</option>
        if ($vues==null || in_array("5",$vues)) {
            $section->addTextBreak(2);
            $section->addTitle("Infrastructure physique", 1);
        }

        // <option value="6">Infrastructure logique</option>
        if ($vues==null || in_array("6",$vues)) {
            $section->addTextBreak(2);
            $section->addTitle("Infrastructure logique", 1);
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

