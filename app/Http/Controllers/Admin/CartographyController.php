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
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Chart;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Line;

class CartographyController extends Controller
{

    public function cartography(Request $request) {

        // converter 
        $html = new \PhpOffice\PhpSpreadsheet\Helper\Html();

        // get parameters
        $granularity = $request->granularity;
        $vues = $request->input('vues', []);

        // get template
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
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

        // cell style
        $fancyTableTitleStyle=array("bold"=>true, 'color' => '006699');
        $fancyTableCellStyle=array("bold"=>true, 'color' => '000000');

        // Title
        $section->addTitle("Cartographie du Système d'information",0);        
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

            // IMAGE
            //$section = $phpWord->addSection();
            $textRun=$section->addTextRun();
            $imageStyle = array(
                'marginTop' => -1,
                'marginLeft' => -1,
                'width' => 100,
                'height' => 100,
                'wrappingStyle' => 'square',
            );            
            $textRun->addImage(public_path('images/cloud.png'), $imageStyle);

            // ENTITIES
            $section->addTitle('Entités', 2);
            $section->addText("Partie de l’organisme (ex. : filiale, département, etc.) ou système d’information en relation avec le SI qui vise à être cartographié.");
            $section->addTextBreak(1);

            // get all entities
            // $section = $phpWord->addSection();
            $entities = Entity::All()->sortBy("name");
            foreach ($entities as $entity) {
                $section->addBookmark("ENTITY".$entity->id);
                $table = $section->addTable(
                        array('borderSize' => 6, 'borderColor' => '006699', 'cellMargin' => 80, 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::START, 'cellSpacing' => 50));
                $table->addRow();
                $table->addCell(8000,array('gridSpan' => 2,'bold'=>true))
                    ->addText($entity->name,$fancyTableTitleStyle);
                $table->addRow();
                $table->addCell(2000)->addText("Description", $fancyTableCellStyle);
                $table->addCell(6000)->addText(
                    htmlspecialchars($html->toRichTextObject($entity->description))
                    );
                $table->addRow();
                $table->addCell(2000)->addText("Niveau de sécurité",$fancyTableCellStyle);
                $table->addCell(6000)->addText(htmlspecialchars($html->toRichTextObject($entity->security_level)));
                $table->addRow();
                $table->addCell(2000)->addText("Point de contact",$fancyTableCellStyle);
                $table->addCell(6000)->addText($entity->contact_point);
                $table->addRow();
                $table->addCell(2000)->addText("Relations",$fancyTableCellStyle);
                $cell=$table->addCell(6000);
                $textRun=$cell->addTextRun();
                foreach ($entity->sourceRelations as $relation) {
                    if ($relation->id!=null)
                        $textRun->addLink('RELATION'.$relation->id, $relation->name, null, null, true);
                    $textRun->addText(' -> ');
                    if ($relation->destination_id!=null)
                        $textRun->addLink('ENTITY'.$relation->destination_id, $entities->find($relation->destination_id)->name, null, null, true);
                    if ($entity->sourceRelations->last() != $relation) 
                        $textRun->addText(", ");                    
                }
                if ((count($entity->sourceRelations)>0)&&(count($entity->destinationRelations)>0))
                    $textRun->addText(", ");
                foreach ($entity->destinationRelations as $relation) {                    
                    $textRun->addLink('RELATION'.$relation->id, $relation->name, null, null, true);
                    $textRun->addText(htmlspecialchars(' <- '));
                    $textRun->addLink('ENTITY'.$relation->source_id, $entities->find($relation->source_id)->name, null, null, true);
                    if ($entity->destinationRelations->last() != $relation)  
                        $textRun->addText(", ");                    
                }
                $table->addRow();
                $table->addCell(2000)->addText("Processus soutenus",$fancyTableCellStyle);
                $table->addCell(6000)->addText("");
                $section->addTextBreak(1);
            }

            // RELATIONS
            $section->addTextBreak(2);
            $section->addTitle('Relations', 2);
            $section->addText("Lien entre deux entités ou systèmes.");
            $section->addTextBreak(1);

            // get all relations
            $relations = Relation::All()->sortBy("name");
            foreach ($relations as $relation) {
                Log::debug('RELATION'.$relation->id);
                $section->addBookmark("RELATION".$relation->id);
                $table = $section->addTable(
                        array('borderSize' => 6, 'borderColor' => '006699', 'cellMargin' => 80, 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::START, 'cellSpacing' => 50));
                $table->addRow();
                $table->addCell(8000,array('gridSpan' => 2))->addText($relation->name,$fancyTableTitleStyle);
                $table->addRow();
                $table->addCell(2000)->addText("Description",$fancyTableCellStyle);
                $table->addCell(6000)->addText(htmlspecialchars($html->toRichTextObject($relation->description)));
                $table->addRow();
                $table->addCell(2000)->addText("Type",$fancyTableCellStyle);
                $table->addCell(6000)->addText($relation->type);
                $table->addRow();
                $table->addCell(1500)->addText("Importance",$fancyTableCellStyle);
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
                $table->addRow();
                $table->addCell(2000)->addText("Lien",$fancyTableCellStyle);
                $cell=$table->addCell(6000);
                $textRun=$cell->addTextRun();
                $textRun->addLink('ENTITY'.$relation->source_id, $entities->find($relation->source_id)->name, null, null, true);
                $textRun->addText(" -> ");
                $textRun->addLink('ENTITY'.$relation->destination_id, $entities->find($relation->destination_id)->name, null, null, true);
                $section->addTextBreak(1);
            }

        }

        // <option value="2">Système d'information</option>
        if ($vues==null || in_array("2",$vues)) {
            $section->addTextBreak(2);
            $section->addTitle("Système d'information", 1);
        }

        // <option value="3">Applications</option>
        if ($vues==null || in_array("3",$vues)) {
            $section->addTextBreak(2);
            $section->addTitle("Applications", 1);
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

        // save a copy
        $filepath=storage_path('app/reports/cartographie-'. Carbon::today()->format("Ymd") .'.docx');

        // Saving the document as OOXML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($filepath);

        // if (file_exists($filepath)) unlink($filepath);
        // $templateProcessor->saveAs();

        // return
        return response()->download($filepath);       
        // return null;
    }

}

