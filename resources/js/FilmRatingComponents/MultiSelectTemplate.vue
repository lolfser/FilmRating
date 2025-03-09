<template>
    <div :id="'filter-item-' + name" class="filter-item">
        <input type="text" :id="'search-'+ name" :placeholder="placeholder">
        <br>
        <div class="selected-options" :id="'selected-options-' +name">
            <span :id="'selected-list-' + name" class="selected-list">keine Auswahl</span>
        </div>
        <ul class="checkbox-list" :id="'checkbox-list-' + name">
            <li v-for="option in options">
                <label>
                    <input type="checkbox" :class="'checkbox-option-' + name" :value="option.id" :checked="isSelected(option.id)">
                    {{ option.name }}
                </label>
            </li>
        </ul>
    </div>
</template>
<script>
export default {
    props: [
        'options', 'name', 'placeholder', 'selectedOptions'
    ],
    methods: {
        isSelected: function (id) {
            let result = false;
            if (typeof this.selectedOptions === "object") {
                this.selectedOptions.forEach(function (value, index) {
                    console.log(id, value)
                    if (id === value) {
                        result = true;
                        return false;
                    }
                    return true;
                });
            }
            return result;
        }
    },
    mounted: function () {
        const value = this.name;
        const filterItem = document.getElementById('filter-item-' + value);
        const searchInput = document.getElementById('search-' + value);
        const checkboxList = document.getElementById('checkbox-list-' + value);
        const selectedList = document.getElementById('selected-list-' + value);
        const checkboxOptions = document.getElementsByClassName('checkbox-option-' + value);

        filterItem.addEventListener('mouseover', function () {
            checkboxList.style.display = "block";
        });

        filterItem.addEventListener('mouseleave', function () {
            checkboxList.style.display = "none";
        });

        searchInput.addEventListener('keyup', function () {
            const filter = searchInput.value.toLowerCase();
            const checkboxes = checkboxList.getElementsByTagName('li');

            for (let i = 0; i < checkboxes.length; i++) {
                const label = checkboxes[i].textContent || checkboxes[i].innerText;
                if (label.toLowerCase().indexOf(filter) > -1) {
                    checkboxes[i].style.display = "";
                } else {
                    checkboxes[i].style.display = "none";
                }
            }
            updateSelectedOptions(checkboxOptions, selectedList);
        });

        checkboxOptions.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                updateSelectedOptions(checkboxOptions, selectedList);
            });
        });

        function updateSelectedOptions(checkboxOptions, selectedList) {
            const selectedValues = Array.from(checkboxOptions)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.parentNode.textContent);

            selectedList.textContent = selectedValues.length > 0 ? selectedValues.join(', ') : 'keine Auswahl';
        }
        updateSelectedOptions(checkboxOptions, selectedList);
    }
}
</script>
<style>
.filter-item {
    position: relative;
    display: inline-block;
    margin-left: 5px;
    margin-right: 5px;
}

.filter-item .selected-list {
    font-size: 12px;
}

.selected-options {
    margin: 0;
    display: inline;
}

.checkbox-list {
    list-style-type: none;
    padding: 5px;
    display: none;
    position: absolute;
    background: white;
    border: 1px solid #ccc;
    z-index: 1;
}

.checkbox-list li {
    background-color: rgba(171, 221, 232, 0.71);
}

.checkbox-list li:nth-child(2n+2) {
    background-color: rgba(171, 232, 208, 0.71);
}

.checkbox-list li {
    margin: 5px 0;
    min-width: 150px;
    display: block;
    cursor: pointer;
}

.checkbox-list li label {
    cursor: pointer;
    display: block;
}

.checkbox-list input {
    margin-right: 5px;
}
</style>
