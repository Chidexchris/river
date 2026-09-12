class FormGenerator {
    constructor(formClassName = null) {
        this.fieldType = null;
        this.totalField = 0;

        if (this.formClassName) {
            this.formClassName = '.' + formClassName;
        } else {
            this.formClassName = '.generate-form';
        }
    }

    extraFields(fieldType) {
        this.fieldType = fieldType;
        let field;
        let title;

        if (this.fieldType === 'file') {
            field = `<select name="extensions" class="form--control form--control--sm select-2" multiple="multiple" required>
                        <option value="jpg">JPG</option>
                        <option value="jpeg">JPEG</option>
                        <option value="png">PNG</option>
                        <option value="pdf">PDF</option>
                        <option value="doc">DOC</option>
                        <option value="docx">DOCX</option>
                        <option value="txt">TXT</option>
                        <option value="xlx">XLX</option>
                        <option value="xlsx">XLSX</option>
                        <option value="csv">CSV</option>
                    </select>`;

            title = `File Extensions`;
        } else {
            field = `<div class="options">
                        <div class="input--group mb-3">
                            <input type="text" class="form--control form--control--sm" name="options[]" required>
                            <span class="input-group-text addOption">
                                <span class="ti ti-circle-plus text--base translate-0" role="button"></span>
                            </span>
                        </div>
                    </div>`;
            title = `Add Options`;
        }

        let html = `<div class="col-12">
                            <div class="row gy-2">
                                <div class="col-sm-4">
                                    <label class="col-form--label required">${title}</label>
                                </div>
                                <div class="col-sm-8">
                                    ${field}
                                </div>
                            </div>
                        </div>`;

        if (
            this.fieldType === 'text' ||
            this.fieldType === 'textarea' ||
            this.fieldType === 'email' ||
            this.fieldType === 'url' ||
            this.fieldType === 'number' ||
            this.fieldType === 'datetime' ||
            this.fieldType === 'date' ||
            this.fieldType === 'time' ||
            this.fieldType === ''
        ) {
            html = '';
        }

        return html;
    }

    addOptions() {
        return `<div class="input--group mb-3">
                    <input type="text" class="form--control form--control--sm" name="options[]" required>
                    <span class="input-group-text removeOption">
                        <span class="ti ti-circle-x text--danger translate-0" role="button"></span>
                    </span>
                </div>`;
    }

    formsToJson(form) {
        let extensions = null;
        let options = [];
        this.fieldType = form.find('[name=form_type]').val();

        if (this.fieldType === 'file') {
            extensions = form.find('[name=extensions]').val();
        }

        if (this.fieldType === 'select' || this.fieldType === 'checkbox' || this.fieldType === 'radio') {
            options = $("[name='options[]']").map(function () {
                return $(this).val();
            }).get();
        }

        return {
            type: this.fieldType,
            is_required: form.find('[name=is_required]').val(),
            label: form.find('[name=form_label]').val(),
            extensions: extensions,
            options: options,
            old_id: form.find('[name=update_id]').val()
        };
    }

    makeFormHtml(formItem, updateId) {
        if (formItem.old_id) {
            updateId = formItem.old_id;
        }

        let hiddenFields = `<input type="hidden" name="form_generator[form_label][]" value="${formItem.label}">
                            <input type="hidden" name="form_generator[form_type][]" value="${formItem.type}">
                            <input type="hidden" name="form_generator[is_required][]" value="${formItem.is_required}">
                            <input type="hidden" name="form_generator[extensions][]" value="${formItem.extensions}">
                            <input type="hidden" name="form_generator[options][]" value="${formItem.options}">`;

        let formsHtml = `
            ${hiddenFields}
            <ul>
                <li><i class="ti ti-tag text--success"></i> Label : <span class="fw-semibold">${formItem.label}</span></li>
                <li><i class="ti ti-forms text--info"></i> Type : <span class="fw-semibold">${formItem.type}</span></li>
                <li><i class="ti ti-asterisk text--danger"></i> Required : <span class="fw-semibold">${formItem.is_required === 'required' ? 'Yes' : 'No'}</span></li>
            </ul>

            <div class="d-flex pt-3 mt-3 border-top d-flex justify-content-center gap-2">
                <button type="button" class="btn btn--sm btn--base editFormData" data-form_item='${JSON.stringify(formItem)}' data-update_id="${updateId}">
                    <span class="ti ti-edit"></span></i> Edit
                </button>
                <button type="button" class="btn btn--sm btn--danger removeFormData">
                    <i class="ti ti-trash"></i> Delete
                </button>
            </div>
        `;

        let html = `<div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="custom--card payment-method-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                            <div class="card-body" id="${updateId}">
                                ${formsHtml}
                            </div>
                        </div>
                    </div>`;

        if (formItem.old_id) {
            html = formsHtml;
            $(`#${formItem.old_id}`).html(html);
        } else {
            $('.addedField').append(html);
        }
    }

    formEdit(element) {
        this.showModal();
        let formItem = element.data('form_item');
        let form = $(this.formClassName);

        form.find('[name=form_type]').val(formItem.type);
        form.find('[name=form_label]').val(formItem.label);
        form.find('[name=is_required]').val(formItem.is_required);
        form.find('[name=update_id]').val(element.data('update_id'))

        let html = '';

        if (formItem.type === 'file') {
            html += `<div class="col-12">
                        <div class="row gy-2">
                            <div class="col-sm-4">
                                <label class="col-form--label required">File Extensions</label>
                            </div>
                            <div class="col-sm-8">
                                <select name="extensions" class="form--control form--control--sm select-2" multiple="multiple" required>
                                    <option value="jpg">JPG</option>
                                    <option value="jpeg">JPEG</option>
                                    <option value="png">PNG</option>
                                    <option value="pdf">PDF</option>
                                    <option value="doc">DOC</option>
                                    <option value="docx">DOCX</option>
                                    <option value="txt">TXT</option>
                                    <option value="xlx">XLX</option>
                                    <option value="xlsx">XLSX</option>
                                    <option value="csv">CSV</option>
                                </select>
                            </div>
                        </div> 
                    </div>`;
        }

        let i = 0;
        let optionItem = '';

        formItem.options.forEach(option => {
            let isRemove = '';

            if (i !== 0) {
                isRemove = `<span class="input-group-text removeOption">
                                <span class="ti ti-xbox-x text--danger translate-0" role="button"></span>
                            </span>`;
            }

            if (i === 0) {
                isRemove = `<span class="input-group-text addOption">
                                <span class="ti ti-circle-plus text--base translate-0" role="button"></span>
                            </span>`;
            }

            i += 1;
            optionItem += `<div class="input--group mb-3">
                                <input type="text" class="form--control form--control--sm" name="options[]" value="${option}" required>
                                ${isRemove}
                            </div>`;
        });

        if (formItem.type === 'select' || formItem.type === 'checkbox' || formItem.type === 'radio') {
            html += `<div class="col-12">
                        <div class="row gy-2">
                            <div class="col-sm-4">
                                <label class="col-form--label required">Add Options</label>
                            </div>
                            <div class="col-sm-8">
                                <div class="options">
                                    ${optionItem}
                                </div>
                            </div>
                        </div>
                    </div>`;
        }

        $('.generatorSubmit').text('Update');

        let extraArea = $('.extra_area');
        extraArea.html(html);
        extraArea.find('select').val(formItem.extensions);
    }

    resetAll() {
        $(formGenerator.formClassName).trigger("reset");
        $('.extra_area').html('');
        $('.generatorSubmit').text('Add');
        $('[name=update_id]').val('');
    }

    closeModal() {
        let modal = $('#formGenerateModal');
        modal.modal('hide');
    }

    showModal() {
        this.resetAll();

        let modal = $('#formGenerateModal');
        modal.modal('show');
    }
}
