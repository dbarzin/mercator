/*
 * Created by David Adams
 * https://codeshack.io/dynamic-select-images-html-javascript/
 *
 * Released under the MIT license
 */
export default class DynamicSelect {

    _escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    constructor(element, options = {}) {
        let defaults = {
            placeholder: 'Select an option',
            columns: 1,
            name: '',
            width: '',
            height: '',
            data: [],
            onChange: function() {}
        };
        this.options = Object.assign(defaults, options);
        this.selectElement = typeof element === 'string' ? document.querySelector(element) : element;
        for(const prop in this.selectElement.dataset) {
            if (this.options[prop] !== undefined) {
                this.options[prop] = this.selectElement.dataset[prop];
            }
        }
        this.name = this.selectElement.getAttribute('name') ? this.selectElement.getAttribute('name') : 'dynamic-select-' + Math.floor(Math.random() * 1000000);
        if (!this.options.data.length) {
            let options = this.selectElement.querySelectorAll('option');
            for (let i = 0; i < options.length; i++) {
                this.options.data.push({
                    value: options[i].value,
                    text: options[i].innerHTML,
                    img: options[i].getAttribute('data-img'),
                    selected: options[i].selected,
                    html: options[i].getAttribute('data-html'),
                    imgWidth: options[i].getAttribute('data-img-width'),
                    imgHeight: options[i].getAttribute('data-img-height')
                });
            }
        }
        this.element = this._template();
        this.selectElement.replaceWith(this.element);
        this._updateSelected();
        this._eventHandlers();
    }

    _template() {
        let optionsHTML = '';
        for (let i = 0; i < this.data.length; i++) {
            let optionWidth = 100 / this.columns;
            let optionContent = '';
            if (this.data[i].html) {
                optionContent = this._escapeHtml(this.data[i].html);
            } else {
                optionContent = `
                    ${this.data[i].img ? `<img src="${this._escapeHtml(this.data[i].img)}" alt="${this._escapeHtml(this.data[i].text)}" class="${this.data[i].imgWidth && this.data[i].imgHeight ? 'dynamic-size' : ''}" style="${this.data[i].imgWidth ? 'width:' + this._escapeHtml(this.data[i].imgWidth) + ';' : ''}${this.data[i].imgHeight ? 'height:' + this._escapeHtml(this.data[i].imgHeight) + ';' : ''}">` : ''}
                    ${this.data[i].text ? '<span class="dynamic-select-option-text">' + this._escapeHtml(this.data[i].text) + '</span>' : ''}
                `;
            }
            optionsHTML += `
                <div class="dynamic-select-option${this.data[i].value == this.selectedValue ? ' dynamic-select-selected' : ''}${this.data[i].text || this.data[i].html ? '' : ' dynamic-select-no-text'}" data-value="${this._escapeHtml(this.data[i].value)}" style="width:${optionWidth}%;${this.height ? 'height:' + this._escapeHtml(this.height) + ';' : ''}">
                    ${optionContent}
                </div>
            `;
        }
        let template = `
            <div class="dynamic-select ${this._escapeHtml(this.name)}"${this.selectElement.id ? ' id="' + this._escapeHtml(this.selectElement.id) + '"' : ''} style="${this.width ? 'width:' + this._escapeHtml(this.width) + ';' : ''}${this.height ? 'height:' + this._escapeHtml(this.height) + ';' : ''}">
                <input type="hidden" name="${this._escapeHtml(this.name)}" value="${this._escapeHtml(this.selectedValue)}">
                <div class="dynamic-select-header" style="${this.width ? 'width:' + this._escapeHtml(this.width) + ';' : ''}${this.height ? 'height:' + this._escapeHtml(this.height) + ';' : ''}"><span class="dynamic-select-header-placeholder">${this._escapeHtml(this.placeholder)}</span></div>
                <div class="dynamic-select-options" style="${this.options.dropdownWidth ? 'width:' + this._escapeHtml(this.options.dropdownWidth) + ';' : ''}${this.options.dropdownHeight ? 'height:' + this._escapeHtml(this.options.dropdownHeight) + ';' : ''}">${optionsHTML}</div>
            </div>
        `;
        let element = document.createElement('div');
        element.innerHTML = template;
        return element;
    }

    _eventHandlers() {
        this.element.querySelectorAll('.dynamic-select-option').forEach(option => {
            option.onclick = () => {
                this.element.querySelectorAll('.dynamic-select-selected').forEach(selected => selected.classList.remove('dynamic-select-selected'));
                option.classList.add('dynamic-select-selected');
                const header = this.element.querySelector('.dynamic-select-header');
                header.replaceChildren(...Array.from(option.childNodes).map(node => node.cloneNode(true)));
                this.element.querySelector('input').value = option.getAttribute('data-value');
                this.data.forEach(data => data.selected = false);
                this.data.filter(data => data.value == option.getAttribute('data-value'))[0].selected = true;
                this.element.querySelector('.dynamic-select-header').classList.remove('dynamic-select-header-active');
                this.options.onChange(option.getAttribute('data-value'), option.querySelector('.dynamic-select-option-text') ? option.querySelector('.dynamic-select-option-text').textContent : '', option);
            };
        });
        this.element.querySelector('.dynamic-select-header').onclick = () => {
            this.element.querySelector('.dynamic-select-header').classList.toggle('dynamic-select-header-active');
        };
        if (this.selectElement.id && document.querySelector('label[for="' + this.selectElement.id + '"]')) {
            document.querySelector('label[for="' + this.selectElement.id + '"]').onclick = () => {
                this.element.querySelector('.dynamic-select-header').classList.toggle('dynamic-select-header-active');
            };
        }
        document.addEventListener('click', event => {
            if (!event.target.closest('.' + this.name) && !event.target.closest('label[for="' + this.selectElement.id + '"]')) {
                this.element.querySelector('.dynamic-select-header').classList.remove('dynamic-select-header-active');
            }
        });
    }

    _updateSelected() {
        if (this.selectedValue) {
            this.element.querySelector('.dynamic-select-header').innerHTML = this.element.querySelector('.dynamic-select-selected').innerHTML;
        }
    }

    get selectedValue() {
        let selected = this.data.filter(option => option.selected);
        selected = selected.length ? selected[0].value : '';
        return selected;
    }

    set data(value) {
        this.options.data = value;
    }

    get data() {
        return this.options.data;
    }

    set selectElement(value) {
        this.options.selectElement = value;
    }

    get selectElement() {
        return this.options.selectElement;
    }

    set element(value) {
        this.options.element = value;
    }

    get element() {
        return this.options.element;
    }

    set placeholder(value) {
        this.options.placeholder = value;
    }

    get placeholder() {
        return this.options.placeholder;
    }

    set columns(value) {
        this.options.columns = value;
    }

    get columns() {
        return this.options.columns;
    }

    set name(value) {
        this.options.name = value;
    }

    get name() {
        return this.options.name;
    }

    set width(value) {
        this.options.width = value;
    }

    get width() {
        return this.options.width;
    }

    set height(value) {
        this.options.height = value;
    }

    get height() {
        return this.options.height;
    }

    // New refresh method with selection of the new element
    refresh(data, selectedValue = null) {
        this.data = data;  // Update the data

        if (selectedValue) {
            // Find the newly added element and mark it as selected
            this.data.forEach(option => {
                if (option.value === selectedValue) {
                    option.selected = true;  // Mark this option as selected
                } else {
                    option.selected = false;  // Unselect others
                }
            });
        }

        const newElement = this._template();  // Create new HTML for the component
        this.element.replaceWith(newElement);  // Replace the current element with the new one
        this.element = newElement;  // Update the reference to the new element

        this._updateSelected();
        this._eventHandlers();  // Reattach event handlers
    }

}
