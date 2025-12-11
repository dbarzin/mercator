import 'bootstrap';
//import "bootstrap-datetime-picker";
/*==================================*/
import $ from 'jquery';
import select2 from 'select2';
/*==================================*/
/* Datatables from : https://datatables.net/download/#bs5/jszip-3.10.1/pdfmake-0.2.7/dt-2.1.8/af-2.7.0/b-3.2.0/b-colvis-3.2.0/b-html5-3.2.0/b-print-3.2.0/cr-2.0.4/date-1.5.4/fc-5.0.4/fh-4.0.1/kt-2.12.1/r-3.0.3/rg-1.5.1/rr-1.5.0/sc-2.4.3/sb-1.8.1/sp-2.3.3/sl-2.1.0/sr-1.4.1 */
import jszip from 'jszip';
import pdfmake from 'pdfmake';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-autofill-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.colVis.mjs';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';
import 'datatables.net-colreorder-bs5';
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
// Import fonts for pdfMake
import pdfFonts from 'pdfmake/build/vfs_fonts';

DataTable.Buttons.jszip(jszip);
DataTable.Buttons.pdfMake(pdfmake);

// Save fonts
pdfmake.vfs = pdfFonts.vfs ?? pdfFonts.default?.vfs;

// Initialize select2
select2($);

// Vérifier que jQuery est bien chargé
window.$ = window.jQuery = $;

// Permet d'utiliser DynamicSelect dans la Blade
window.DynamicSelect = DynamicSelect;
window.Swal = Swal;
window.moment = moment;
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

    // Shortcuts
    document.addEventListener('keydown', (e) => {
        if (e.ctrlKey && e.key === 'n') {
            e.preventDefault();
            document.getElementById('btn-new').click();
        }
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            document.getElementById('btn-save').click();
        }
        if (e.ctrlKey && e.key === 'd') {
            e.preventDefault();
            document.getElementById('btn-duplicate').click();
        }
        if (e.key === 'Escape') {
            document.getElementById('btn-cancel').click();
        }
    });

    // CKEditor
    var allEditors = document.querySelectorAll('.ckeditor');
    for (var i = 0; i < allEditors.length; ++i) {
        ClassicEditor.create(
            allEditors[i], {
                removePlugins: ['Table', 'TableToolbar', 'EasyImage', 'ImageUpload', 'MediaEmbed'],
                toolbar: {
                    items: ['undo', 'redo', '|', 'bold', 'italic', '|', 'link', '|', 'numberedList', 'bulletedList'],
                    shouldNotGroupWhenFull: true
                }
            }
        )
            .then(editor => {
                document.querySelector('.ckeditor').style.visibility = 'visible';
            })
            .catch(error => console.error(error));
        ;
    }

    // Select2
    $('.select2').each(function () {
        // skip already initialised select2
        // used by CPE search
        if (this.id != '')
            $(this).select2({
                placeholder: '...',
                allowClear: true
            });
    });

    $(".select2-free").select2({
        placeholder: "...",
        allowClear: true,
        tags: true
    });

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
        templateSelection: function (data) {
            const riskClasses = {
                4: 'highRisk',
                3: 'mediumRisk',
                2: 'lowRisk',
                1: 'veryLowRisk'
            };

            const riskClass = riskClasses[data.id];
            return riskClass ? `<span class="${riskClass}">${data.text}</span>` : data.text;
        },
        escapeMarkup: function (markup) {
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
        setTimeout(function () {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        }, 275);
    })

    let sidebar = document.querySelector(".sidebar");
    let dropdowns = document.querySelectorAll(".sidebar .dropdown-toggle");

    dropdowns.forEach(dropdown => {
        dropdown.addEventListener("click", function () {
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
        $('input[data-check=' + $(this).prop('id') + ']:not(:checked)').each(function () {
            $(this).prop('checked', true);
        });
    });

    // =====================================================================
    // Dynamic Select (image upload and select)
    document.querySelectorAll('[data-dynamic-select]').forEach(select => new DynamicSelect(select));

    // ======================================================================
    // Download Graph as SVG
    // ======================================================================
    // document.getElementById("downloadSvg").onclick = async function (e) {
    $("#downloadSvg").on("click", async function (e) {
        e.preventDefault();

        const svg = document.querySelector("#graph svg");
        if (!svg) {
            alert("Aucun graphe trouvé dans #graph");
            return;
        }

        // --- Clone pour travailler hors DOM
        const svgClone = svg.cloneNode(true);

        // --- Namespaces requis
        svgClone.setAttribute("xmlns", "http://www.w3.org/2000/svg");
        svgClone.setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:xlink", "http://www.w3.org/1999/xlink");

        // --- Embarque toutes les <image> en data URL
        const xlinkNS = "http://www.w3.org/1999/xlink";
        const images = Array.from(svgClone.querySelectorAll("image"));

        async function urlToDataURL(url) {
            const abs = new URL(url, window.location.href).href;
            const res = await fetch(abs, {credentials: "same-origin"});
            if (!res.ok) throw new Error(`Fetch image failed: ${abs}`);
            const blob = await res.blob();
            return await new Promise((resolve) => {
                const reader = new FileReader();
                reader.onload = () => resolve(reader.result);
                reader.readAsDataURL(blob);
            });
        }

        await Promise.all(images.map(async (img) => {
            const href = img.getAttribute("href") ||
                img.getAttributeNS(xlinkNS, "href") ||
                img.getAttribute("xlink:href");
            if (!href || href.startsWith("data:")) return;

            try {
                const dataUrl = await urlToDataURL(href);
                img.setAttribute("href", dataUrl);
                img.setAttributeNS(xlinkNS, "xlink:href", dataUrl);
            } catch (err) {
                console.warn("Impossible d’embarquer l’image:", href, err);
            }
        }));

        // --- Supprime les liens (variante 1)
        const links = svgClone.querySelectorAll("a");
        links.forEach(link => {
            link.removeAttribute("href");
            link.removeAttribute("xlink:href");
            link.removeAttributeNS(xlinkNS, "href");
        });

        // --- Sérialisation propre
        const serializer = new XMLSerializer();
        let source = serializer.serializeToString(svgClone);
        source = source.replace(/<\?\s*xml[^>]*\?>\s*/i, "");
        source = '<\?xml version="1.0" encoding="UTF-8" standalone="no"?>\n' + source;

        // --- Téléchargement
        const blob = new Blob([source], {type: "image/svg+xml;charset=utf-8"});
        const url = URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;

        // Ajout de la date et de l’heure au nom du fichier
        const now = new Date();
        const timestamp = now.getFullYear() +
            String(now.getMonth() + 1).padStart(2, "0") +
            String(now.getDate()).padStart(2, "0") +
            String(now.getHours()).padStart(2, "0") +
            String(now.getMinutes()).padStart(2, "0");

        a.download = `graph-${timestamp}.svg`;
        document.body.appendChild(a);
        a.click();
        a.remove();
        URL.revokeObjectURL(url);
    });

    // =======================================================
    // Image Select
    // =======================================================

    (function ($) {
        // Construit le dataset pour DynamicSelect à partir du <select> et de ses data-*
        function buildImagesData($select) {
            const ds = $select.data();
            const icons = Array.isArray(ds.icons) ? ds.icons : [];
            const selected = String(ds.selected ?? $select.val() ?? '-1');
            const imgWidth = '120px';
            const imgHeight = '120px';

            const list = [];

            // entrée "par défaut"
            list.push({
                value: '-1',
                img: ds.defaultImg,
                imgWidth,
                imgHeight,
                selected: (selected === '-1' || selected === ''),
            });

            // entrées depuis la liste d'IDs
            icons.forEach((id) => {
                const value = String(id);
                list.push({
                    value,
                    img: String(ds.urlTemplate).replace(':id', value),
                    imgWidth,
                    imgHeight,
                    selected: value === selected,
                });
            });

            return {list, selected};
        }

        // Récupérer la valeur courante depuis le plugin (fallback si API différente)
        function tryGetValue(dynamicSelect, fallback) {
            if (dynamicSelect && typeof dynamicSelect.getValue === 'function') {
                return dynamicSelect.getValue();
            }
            if (dynamicSelect && typeof dynamicSelect.value !== 'undefined') {
                return dynamicSelect.value;
            }
            if (dynamicSelect && typeof dynamicSelect.selectedValue !== 'undefined') {
                return dynamicSelect.selectedValue;
            }
            return fallback ?? '';
        }

        // Attacher la gestion d’upload (optionnelle)
        function attachUploadHandler($select, dynamicSelect, imagesData) {
            const uploadSelector = $select.data('upload');
            if (!uploadSelector) return;

            const $file = $(uploadSelector);
            if (!$file.length) return;

            $file.on('change', function (e) {
                const file = e.target.files && e.target.files[0];
                if (!file) return;

                if (file.type !== 'image/png') {
                    alert('Select a PNG image.');
                    return;
                }
                // taille du fichier (utiliser file.size, pas img.size)
                if (file.size > 65535) { // ~65 KB
                    alert('Image size must be < 65KB');
                    return;
                }

                const tmpUrl = URL.createObjectURL(file);
                const probe = new Image();

                probe.onload = function () {
                    // borne stricte 256x256
                    if (probe.width > 256 || probe.height > 256) {
                        alert('Could not be more than 256x256 pixels.');
                        URL.revokeObjectURL(tmpUrl);
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function (ev) {
                        const newValue = file.name; // adapte si besoin (id unique, etc.)

                        // 1) Ajouter la nouvelle icône dans la liste
                        imagesData.push({
                            value: newValue,
                            img: ev.target.result,  // base64 pour l’aperçu
                            imgHeight: '100px',
                        });

                        // 2) Rafraîchir + sélectionner la nouvelle valeur
                        if (typeof dynamicSelect.refresh === 'function') {
                            dynamicSelect.refresh(imagesData, newValue);
                        }
                        // fallback si refresh n’applique pas la sélection
                        if (typeof dynamicSelect.setValue === 'function') {
                            dynamicSelect.setValue(newValue);
                        }

                        // 3) Synchroniser la valeur du <select> (ce qui sera posté)
                        $select.val(newValue).trigger('change');
                    };
                    reader.readAsDataURL(file);

                    URL.revokeObjectURL(tmpUrl);
                };

                probe.onerror = function () {
                    URL.revokeObjectURL(tmpUrl);
                    alert('Invalid image file.');
                };

                probe.src = tmpUrl;
            });
        }

        // Initialisation d’un picker
        function initImageSelect($select) {
            if (!$select.length) return;
            if (typeof window.DynamicSelect !== 'function') return;

            const {list: imagesData, selected} = buildImagesData($select);

            // s’assurer que le <select> a la bonne valeur initiale
            $select.val(selected);

            const dynamicSelect = new DynamicSelect('#' + $select.attr('id'), {
                columns: 2,
                height: '140px',
                width: '160px',
                dropdownWidth: '300px',
                placeholder: 'Select an icon',
                data: imagesData,
                // callbacks si supportés par le plugin
                onChange: function (value) {
                    $select.val(value).trigger('change');
                },
                onSelect: function (value) {
                    $select.val(value).trigger('change');
                }
            });

            // stocker la ref si utile ailleurs
            $select.data('dynamicSelect', dynamicSelect);

            // synchroniser à l’init (filet de sécurité)
            $select.val(tryGetValue(dynamicSelect, selected)).trigger('change');

            // Attacher l’upload si présent
            attachUploadHandler($select, dynamicSelect, imagesData);
        }

        // Auto-init sur toutes les pages
        $(function () {
            $('select.js-icon-picker[data-icons]').each(function () {
                initImageSelect($(this));
            });
        });

    })(jQuery);

    /* GRAPHVIZ */

    const container = document.getElementById('graph-container');
    const handle = document.querySelector('.graph-resize-handle');

    let isDragging = false;

    handle.addEventListener('mousedown', function (e) {
        isDragging = true;
        document.body.style.cursor = 'ns-resize';
    });

    document.addEventListener('mousemove', function (e) {
        if (!isDragging) return;

        const newHeight = e.clientY - container.getBoundingClientRect().top;

        // Limites min et max
        if (newHeight > 100 && newHeight < window.innerHeight - 100) {
            container.style.height = newHeight + 'px';
        }
    });

    document.addEventListener('mouseup', function () {
        isDragging = false;
        document.body.style.cursor = 'default';
    });
    
});
