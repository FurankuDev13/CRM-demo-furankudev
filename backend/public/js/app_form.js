var $collectionHolder;
var $collectionHolder2;

// setup an "add a tag" link
var $addCompanyAddressButton = $('<button type="button" class="add_companyAddress_link button is-primary">Ajouter une autre addresse</button>');
var $newLinkLi = $('<li></li>').append($addCompanyAddressButton);

function addCompanyAddressForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $('div#company_companyAddresses').data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');
   
    var newForm = prototype;
    console.log(prototype);
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);
    newForm = newForm.replace(/__name__/g, index);
    
    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
}

// setup an "add a tag" link
var $addRequestDetailButton = $('<button type="button" class="add_requestDetail_link button is-primary">Ajouter un autre produit</button>');
var $newLinkLi = $('<li></li>').append($addRequestDetailButton);

function addRequestDetailForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $('div#request_form_requestDetails').data('prototype');

    // get the new index
    var index = $collectionHolder2.data('index');
   
    var newForm = prototype;
    console.log(prototype);
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);
    newForm = newForm.replace(/__name__/g, index);
    
    // increase the index with one for the next item
    $collectionHolder2.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
}

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    $collectionHolder = $('ul.companyAddresses');
    $collectionHolder2 = $('ul.requestDetails');

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);
    $collectionHolder2.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);
    $collectionHolder2.data('index', $collectionHolder2.find(':input').length);

    $addCompanyAddressButton.on('click', function(e) {
        // add a new tag form (see next code block)
        addCompanyAddressForm($collectionHolder, $newLinkLi);
    });

    $addRequestDetailButton.on('click', function(e) {
        // add a new tag form (see next code block)
        addRequestDetailForm($collectionHolder2, $newLinkLi);
    });
   
});

