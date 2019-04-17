Dropzone.autoDiscover = false;
$(document).ready(function(){
    var referenceList = new ReferenceList($('.js-reference-list'));

    initializeDropzone(referenceList);


});


// todo - use Webpack Encore so ES6 syntax is transpiled to ES5
class ReferenceList
{
    constructor($element) {
        this.$element = $element;
        this.references = [];
        this.sortable = Sortable.create(this.$element[0], {
            handle: '.drag-handle',
            animation: 150,
            onEnd: () => {
                $.ajax({
                    'url': this.$element.data('url')+"/reorder",
                    'method': 'POST',
                    'data': JSON.stringify(this.sortable.toArray())
                });
            }
        });
        this.render();

        this.$element.on('click', '.js-reference-delete', (event) => {
            this.handleReferenceDelete(event);
        });

        this.$element.on('blur', '.js-reference-edit', (event) => {
            this.handleReferenceEdit(event);
        });

        $.ajax({
            url: this.$element.data('url')
        }).then(data => {
            this.references = data;
            this.render();
        })
    }

    addReference(reference){
        this.references.push(reference);
        this.render();
    }

    handleReferenceDelete(event){
        const $li = $(event.currentTarget.closest('tr'));
        const id = $li.data('id');
        $li.addClass('disabled');

        $.ajax({
            'url': '/admin/custom-products/references/'+id,
            'method': 'DELETE',
        }).then(() => {
            this.references = this.references.filter(reference => {
                return reference.id !== id;
            });
            this.render();
        });
    }

    handleReferenceEdit(event){
        const $li = $(event.currentTarget.closest('tr'));
        const id = $li.data('id');
        const reference = this.references.find(reference => {
            return reference.id === id;
        });

        reference.title = $(event.currentTarget).val();

        $.ajax({
            'url': '/admin/custom-products/references/'+id,
            'method': 'PUT',
            'data': JSON.stringify(reference)
        });
    }

    render() {
        const itemsHtml = this.references.map(reference => {
            return `
<tr data-id="${reference.id}">
    <td style="vertical-align: middle !important;" class="text-center"><i class="drag-handle fas fa-arrows-alt fa-2x"></i></td>
    <td style="vertical-align: middle !important;"><input type="text" class="form-control js-reference-edit" value="${reference.title}" style="width: auto;"></td>
    <td style="vertical-align: middle !important;" class="text-center"><img src="/images/product-references/${reference.filename}" height="50"></td>
    <td style="vertical-align: middle !important;" class="text-center"><button class="btn btn-sm btn-danger js-reference-delete"><i class="fas fa-trash"></i></td>
</tr>
`
        });
        this.$element.html(itemsHtml.join(''));
    }
}


/**
 * @param {ReferenceList} referenceList
 */
function initializeDropzone(referenceList){
    var $formElement = document.querySelector('.js-reference-dropzone');

    if(!$formElement){
        return;
    }

    var dropzone = new Dropzone($formElement, {
        paramName: 'reference',
        init: function() {
            this.on('success', function(file, data){
                referenceList.addReference(data);
            }),
            this.on('error', function(file, data){
                if(data.detail){
                    this.emit('error', file, data.detail);
                }
            });
        },
    })
}