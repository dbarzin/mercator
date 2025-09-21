// ------------------------------------------------
// CPE
// ------------------------------------------------
$('#vendor-selector').select2({
    placeholder: 'Start typing to search',
    tags: true, // Permet d'ajouter de nouvelles valeurs si elles ne sont pas dans les résultats
    ajax: {
        url: '/admin/cpe/search/vendors',
        dataType: 'json', // Assurez-vous que le backend renvoie bien du JSON
        delay: 250, // Ajoute un délai pour éviter les requêtes excessives
        data: function (params) {
            return {
                // 'a' for Applications
                // 'h' for Hardware
                // 'o' for Operating Systems
                part: window.cpePart,
                search: params.term || '' // Ajoute une gestion des cas où params.term est undefined
            };
        },
        processResults: function (data) {
            return {
                results: data.map(function (vendor) {
                    return {
                        id: vendor.name,
                        text: vendor.name
                    };
                })
            };
        },
        cache: true // Active le cache pour optimiser les requêtes
    },
    minimumInputLength: 1 // Empêche la requête tant qu'un caractère n'est pas tapé
});

// ------------------------------------------------
$('#product-selector').select2({
    placeholder: 'Start typing to search',
    tags: true, // Permet d'ajouter de nouvelles valeurs si elles ne sont pas dans les résultats
    ajax: {
        url: '/admin/cpe/search/products',
        dataType: 'json', // Assurez-vous que le backend renvoie bien du JSON
        delay: 250, // Ajoute un délai pour éviter les requêtes excessives
        data: function (params) {
            return {
                part: window.cpePart,
                vendor: $("#vendor-selector").val(),
                search: params.term || '' // Ajoute une gestion des cas où params.term est undefined
            };
        },
        processResults: function (data) {
            return {
                results: data.map(function (product) {
                    return {
                        id: product.name,
                        text: product.name
                    };
                })
            };
        },
        cache: true // Active le cache pour optimiser les requêtes
    },
    minimumInputLength: 0 // Empêche la requête tant qu'un caractère n'est pas tapé
});

// ------------------------------------------------
$('#version-selector').select2({
    placeholder: 'Start typing to search',
    tags: true, // Permet d'ajouter de nouvelles valeurs si elles ne sont pas dans les résultats
    ajax: {
        url: '/admin/cpe/search/versions',
        dataType: 'json', // Assurez-vous que le backend renvoie bien du JSON
        delay: 250, // Ajoute un délai pour éviter les requêtes excessives
        data: function (params) {
            return {
                part: window.cpePart,
                vendor: $("#vendor-selector").val(),
                product: $("#product-selector").val(),
                search: params.term || '' // Ajoute une gestion des cas où params.term est undefined
            };
        },
        processResults: function (data) {
            return {
                results: data.map(function (version) {
                    return {
                        id: version.name,
                        text: version.name
                    };
                })
            };
        },
        cache: true // Active le cache pour optimiser les requêtes
    },
    minimumInputLength: 0 // Empêche la requête tant qu'un caractère n'est pas tapé
});

// ===========
// CPE Guesser
// ===========
function generateCPEList(data) {
    let ret = '<div style="max-height: 300px; overflow-y: scroll;">';
    ret += '<table class="table compact">'
    ret += '<thead><tr><th>Vendor</th><th>Product</th><th></th></tr></thead>';
    data.forEach(function (element) {
        ret += '<tr>';
        ret += '<td>' + element.vendor_name + '</td>';
        ret += '<td>' + element.product_name + '</td>';
        ret += '<td>' + '<a class="select_cpe" data-vendor="' + element.vendor_name + '" data-product="' + element.product_name + '" href="#"> <i class="bi bi-window-plus" style="color:green"></i></a>'
        ret += '</td>';
        ret += '</tr>';
    });
    ret += '</table></div>';
    return ret;
}

// CPE Guesser window
$('#guess').click(function (event) {
    let name = $("#name").val();
    console.log(name);
    $.get("/admin/cpe/search/guess?search=" + encodeURIComponent(name))
        .then((result) =>
            Swal.fire({
                title: "Matching",
                html: generateCPEList(result),
                didOpen(popup) {
                    $('.select_cpe').on('click', function (e) {
                        e.preventDefault();
                        let vendor = $(this).data('vendor');
                        $("#vendor-selector").append('<option>' + vendor + '</option>');
                        $("#vendor-selector").val(vendor);
                        let product = $(this).data('product');
                        $("#product-selector").append('<option>' + product + '</option>');
                        $("#product-selector").val(product);
                        $("#version-selector").append('<option></option>');
                        $("#version-selector").val(null);
                        Swal.close();
                    })
                },
                showConfirmButton: false,
                showCancelButton: true,
                customClass: {
                    container: {
                        'max-height': "6em",
                        'overflow-y': 'scroll',
                        'width': '100%',
                    }
                }
            }));
});
