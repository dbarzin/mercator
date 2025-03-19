import 'bootstrap';
//import "bootstrap-datetime-picker";
/*==================================*/
import $ from 'jquery';
import select2 from 'select2';
/*==================================*/
/* Datatables from : https://datatables.net/download/#bs5/jszip-3.10.1/pdfmake-0.2.7/dt-2.1.8/af-2.7.0/b-3.2.0/b-colvis-3.2.0/b-html5-3.2.0/b-print-3.2.0/cr-2.0.4/date-1.5.4/fc-5.0.4/fh-4.0.1/kt-2.12.1/r-3.0.3/rg-1.5.1/rr-1.5.0/sc-2.4.3/sb-1.8.1/sp-2.3.3/sl-2.1.0/sr-1.4.1 */
import jszip from 'jszip';
import pdfmake from 'pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-autofill-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.colVis.mjs';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';
import 'datatables.net-colreorder-bs5';
import DateTime from 'datatables.net-datetime';
import 'datatables.net-fixedcolumns-bs5';
import 'datatables.net-fixedheader-bs5';
import 'datatables.net-keytable-bs5';
import 'datatables.net-responsive-bs5';
import 'datatables.net-rowreorder-bs5';
import 'datatables.net-scroller-bs5';
import 'datatables.net-searchbuilder-bs5';
import 'datatables.net-searchpanes-bs5';
import 'datatables.net-select-bs5';
import 'datatables.net-staterestore-bs5';
//========================================
import "datatables.net-bs5/css/dataTables.bootstrap5.css"
//==========================================
import DynamicSelect from "./DynamicSelect"
import moment from "moment";
import Swal from "sweetalert2"
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
// DropZone
import Dropzone from 'dropzone';
import "dropzone/dist/dropzone.css";

DataTable.Buttons.jszip(jszip);
DataTable.Buttons.pdfMake(pdfmake);

// Initialize select2
select2($);

// Vérifier que jQuery est bien chargé
window.$ = window.jQuery = $;

// Permet d'utiliser DynamicSelect dans la Blade
window.DynamicSelect = DynamicSelect;
window.Swal=Swal;
window.moment=moment;
window.DataTable = DataTable;
window.select2 = select2;
window.Dropzone = Dropzone;

// Désactive la configuration automatique de Dropzone
Dropzone.autoDiscover = false;

document.addEventListener("DOMContentLoaded", function () {

    window._token = $('meta[name="csrf-token"]').attr('content')

    moment.updateLocale('en', {
    week: {dow: 1} // Monday is the first day of the week
    })

    // Initialiser DataTables sur les éléments ayant la classe .datatable
    $(".datatable").DataTable();

  // CKEditor
  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
        allEditors[i], {
            removePlugins: ['Table', 'TableToolbar', 'EasyImage','ImageUpload','MediaEmbed'],
	        toolbar: {
                  items: [ 'undo', 'redo', '|','bold', 'italic', '|', 'link', '|', 'numberedList', 'bulletedList' ],
		          shouldNotGroupWhenFull: true
              }
        }
    )
    .then(editor => {
        document.querySelector('.ckeditor').style.visibility = 'visible';
    })
    .catch(error => console.error(error));;
  }

  // Select2
  $('.select2').select2();

  $(".select2-free").select2({
        placeholder: "...",
        allowClear: true,
        tags: true
    })

  $('.select-all').click(function () {
    let $select2 = $(this).parent().siblings('.select2')
    $select2.find('option').prop('selected', 'selected')
    $select2.trigger('change')
  })
  $('.deselect-all').click(function () {
    let $select2 = $(this).parent().siblings('.select2')
    $select2.find('option').prop('selected', '')
    $select2.trigger('change')
  })

  // Risk style on select2 options
  $('.risk').select2({
    templateSelection: function(data) {
        const riskClasses = {
            4: 'highRisk',
            3: 'mediumRisk',
            2: 'lowRisk',
            1: 'veryLowRisk'
        };

        const riskClass = riskClasses[data.id];
        return riskClass ? `<span class="${riskClass}">${data.text}</span>` : data.text;
    },
    escapeMarkup: function(markup) {
        return markup;
    }
  });

  $('.treeview').each(function () {
    var shouldExpand = false
    $(this).find('li').each(function () {
      if ($(this).hasClass('active')) {
        shouldExpand = true
      }
    })
    if (shouldExpand) {
      $(this).addClass('active')
    }
  })

  // ====================================================================
  // Sidebar

  $('button.sidebar-toggler').click(function () {
      setTimeout(function() {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
      }, 275);
  })

    let sidebar = document.querySelector(".sidebar");
    let dropdowns = document.querySelectorAll(".sidebar .dropdown-toggle");

    dropdowns.forEach(dropdown => {
        dropdown.addEventListener("click", function() {
            // Ferme les autres menus
            document.querySelectorAll(".sidebar .collapse.show").forEach(openMenu => {
                if (openMenu !== this.nextElementSibling) {
                    openMenu.classList.remove("show");
                }
            });

            // Centrer l'élément ouvert dans la scrollbar
            setTimeout(() => {
                let rect = this.getBoundingClientRect();
                let sidebarRect = sidebar.getBoundingClientRect();
                sidebar.scrollTo({
                    top: sidebar.scrollTop + rect.top - sidebarRect.top - 50,
                    behavior: 'smooth' // Ajoute une transition douce
                });
            }, 100);
        });
    });

    let openMenu = document.querySelector(".sidebar .collapse.show");

    if (openMenu) {
        setTimeout(() => {
            let rect = openMenu.getBoundingClientRect();
            let sidebarRect = sidebar.getBoundingClientRect();
            sidebar.scrollTop += rect.top - sidebarRect.top - (sidebar.clientHeight / 2) + (rect.height / 2);
        }, 300);
    }

  // Accordion JS toggle
  $('.accordion').click(function () {
      $(this).toggleClass('active');
      $(this).next('.panel').toggleClass('active');
  })

  $('.check-all-wrapper input').click(function (e) {
      console.log("check-all-wrapper");
      e.preventDefault();
      $('input[data-check='+$(this).prop('id')+']:not(:checked)').each(function (){
         $(this).prop('checked', true);
      });
  });

  // =====================================================================
  // Dynamic Select (image upload and select)
  document.querySelectorAll('[data-dynamic-select]').forEach(select => new DynamicSelect(select));

});
