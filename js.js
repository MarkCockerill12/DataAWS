console.log("js.js script loaded");

// Define global functions first
function showHelpModal() {
    console.log("showHelpModal called");
    const helpModal = document.getElementById('helpModal');
    if (helpModal) {
        helpModal.style.display = 'block';
    }
}

function hideHelpModal() {
    const helpModal = document.getElementById('helpModal');
    if (helpModal) {
        helpModal.style.display = 'none';
    }
}

function openTab(evt, tabName) {
    const tabTitle = document.getElementById('tabTitle');
    const tabDescription = document.getElementById('tabDescription');
    const tabImage = document.getElementById('tabImage');

    tabTitle.style.display = 'block';
    tabDescription.style.display = 'block';
    tabImage.style.display = 'block';

    if (tabName === 'SELECT') {
        tabTitle.textContent = 'SELECT Function';
        tabDescription.textContent = 'Explanation and examples for the SELECT function.';
        tabImage.src = 'select_example.png';
        tabImage.alt = 'SELECT Example';
    } else if (tabName === 'INSERT') {
        tabTitle.textContent = 'INSERT Function';
        tabDescription.textContent = 'Explanation and examples for the INSERT function.';
        tabImage.src = 'insert_example.png';
        tabImage.alt = 'INSERT Example';
    } else if (tabName === 'UPDATE') {
        tabTitle.textContent = 'UPDATE Function';
        tabDescription.textContent = 'Explanation and examples for the UPDATE function.';
        tabImage.src = 'update_example.png';
        tabImage.alt = 'UPDATE Example';
    } else if (tabName === 'DELETE') {
        tabTitle.textContent = 'DELETE Function';
        tabDescription.textContent = 'Explanation and examples for the DELETE function.';
        tabImage.src = 'delete_example.png';
        tabImage.alt = 'DELETE Example';
    }

    const tablinks = document.querySelectorAll('.tablink');
    tablinks.forEach(link => {
        link.className = link.className.replace(' active', '');
    });

    evt.currentTarget.className += ' active';
}

// Single DOMContentLoaded event listener
document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM fully loaded");

    // Add help button event listener
    const helpButton = document.getElementById('helpButton');
    if (helpButton) {
        helpButton.addEventListener('click', showHelpModal);
        console.log("Help button event listener added");
    } else {
        console.log("Help button not found");
    }

    // Add close button event listener
    const closeButton = document.querySelector('.close-button');
    if (closeButton) {
        closeButton.addEventListener('click', hideHelpModal);
        console.log("Close button event listener added");
    } else {
        console.log("Close button not found");
    }

    // Add window click event listener
    window.addEventListener('click', function(event) {
        const helpModal = document.getElementById('helpModal');
        if (event.target == helpModal) {
            hideHelpModal();
        }
    });
    console.log("Window click event listener added");

    const tablinks = document.querySelectorAll('.tablink');
    tablinks.forEach(tablink => {
        tablink.addEventListener('click', function(event) {
            openTab(event, this.textContent.toUpperCase());
        });
    });
    console.log("Tab link event listeners added");

    // Update the variable for whatever query type you select
    document.querySelectorAll('.options input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const selectedOption = this.nextElementSibling.getAttribute('data-txt');
            document.getElementById('selectedOption').value = selectedOption;

            const titleLabel = document.getElementById('titleLabel');
            const contentLabel = document.getElementById('contentLabel');
            const THEquery = document.querySelector('.THEquery');
            const extraParams = document.getElementById('extraParams');
            const orderByFieldType = document.getElementById('orderByFieldType');
            const secondParam = document.querySelector('.secondParam');
            const thirdParam = document.querySelector('.thirdParam');
            const whereLabel = document.getElementById('whereLabel');

            // Clear all input fields
            document.querySelectorAll('input[type="text"], select').forEach(input => {
                input.value = '';
                if (input.tagName === 'SELECT') {
                    input.selectedIndex = 0;
                }
            });

            if (selectedOption === 'SELECT') {
                THEquery.style.display = 'block';
                titleLabel.textContent = 'SELECT: ';
                contentLabel.textContent = 'FROM: ';
                whereLabel.textContent = 'WHERE:';
                secondParam.style.display = 'block';
                thirdParam.style.display = 'block';
                extraParams.style.display = 'block';
                orderByFieldType.style.display = 'none';
            } else if (selectedOption === 'INSERT') {
                THEquery.style.display = 'block';
                titleLabel.textContent = 'INSERT INTO: ';
                contentLabel.textContent = 'VALUES: ';
                whereLabel.textContent = 'you shouldnt see this lol';
                secondParam.style.display = 'block';
                thirdParam.style.display = 'none';
                extraParams.style.display = 'none';
            } else if (selectedOption === 'UPDATE') {
                THEquery.style.display = 'block';
                titleLabel.textContent = 'UPDATE: ';
                contentLabel.textContent = 'SET: ';
                whereLabel.textContent = 'WHERE:';
                secondParam.style.display = 'block';
                thirdParam.style.display = 'block';
                extraParams.style.display = 'none';
            } else if (selectedOption === 'DELETE') {
                THEquery.style.display = 'block';
                titleLabel.textContent = 'DELETE FROM: ';
                contentLabel.textContent = 'you shouldnt see this lol: ';
                secondParam.style.display = 'none';
                thirdParam.style.display = 'block';
                extraParams.style.display = 'none';
            } else {
                THEquery.style.display = 'none';
                secondParam.style.display = 'none';
                thirdParam.style.display = 'none';
                extraParams.style.display = 'none';
                orderByFieldType.style.display = 'none';
            }
        });
    });

    const groupByElement = document.getElementById('groupBy');
    if (groupByElement) {
        groupByElement.addEventListener('change', function() {
            const groupByValue = document.getElementById('groupByValue');
            if (this.value === 'groupBy') {
                groupByValue.style.display = 'block';
            } else {
                groupByValue.style.display = 'none';
            }
        });
    }

    const orderByElement = document.getElementById('orderBy');
    if (orderByElement) {
        orderByElement.addEventListener('change', function() {
            const orderByValue = document.getElementById('orderByValue');
            const orderByFieldType = document.getElementById('orderByFieldType');
            if (this.value !== 'none') {
                orderByValue.style.display = 'block';
                orderByFieldType.style.display = 'block';
            } else {
                orderByValue.style.display = 'none';
                orderByFieldType.style.display = 'none';
            }
        });
    }

    const orderByFieldTypeElement = document.getElementById('orderByFieldType');
    if (orderByFieldTypeElement) {
        orderByFieldTypeElement.addEventListener('change', function() {
            const orderByValue = document.getElementById('orderByValue');
            if (this.value !== 'none') {
                orderByValue.style.display = 'block';
            } else {
                orderByValue.style.display = 'none';
            }
        });
    }

    const asElement = document.getElementById('as');
    if (asElement) {
        asElement.addEventListener('change', function() {
            const asValue = document.getElementById('asValue');
            if (this.value === 'as') {
                asValue.style.display = 'block';
            } else {
                asValue.style.display = 'none';
            }
        });
    }

    const addTitleFieldButton = document.getElementById('addTitleFieldButton');
    if (addTitleFieldButton) {
        addTitleFieldButton.addEventListener('click', function() {
            const dynamicTitleFields = document.getElementById('dynamicTitleFields');
            const newField = document.createElement('div');
            newField.innerHTML = `
                <label>,</label>
                <select name="dynamicTitleFieldType[]">
                    <option value="none">None</option>
                    <option value="max">MAX</option>
                    <option value="min">MIN</option>
                    <option value="sum">SUM</option>
                    <option value="avg">AVG</option>
                    <option value="count">COUNT</option>
                </select>
                <input type="text" name="dynamicTitleField[]" placeholder="Title Field" />
                <select name="dynamicAs[]">
                    <option value="none">None</option>
                    <option value="as">AS</option>
                </select>
                <input type="text" name="dynamicAsValue[]" placeholder="AS Value" style="display: none;" />
                <button type="button" class="removeFieldButton">Remove</button>
            `;
            dynamicTitleFields.appendChild(newField);

            const asSelect = newField.querySelector('select[name="dynamicAs[]"]');
            const asValueInput = newField.querySelector('input[name="dynamicAsValue[]"]');
            asSelect.addEventListener('change', function() {
                if (this.value === 'as') {
                    asValueInput.style.display = 'inline';
                } else {
                    asValueInput.style.display = 'none';
                }
            });

            newField.querySelector('.removeFieldButton').addEventListener('click', function() {
                dynamicTitleFields.removeChild(newField);
            });
        });
    }

    const addFromFieldButton = document.getElementById('addFromFieldButton');
    if (addFromFieldButton) {
        addFromFieldButton.addEventListener('click', function() {
            const dynamicFromFields = document.getElementById('dynamicFromFields');
            const newField = document.createElement('div');
            newField.innerHTML = `
                <label>,</label>
                <input type="text" name="dynamicFromField[]" placeholder="second Field" />
                <input type="text" name="dynamicFromValue[]" placeholder="Value" />
                <button type="button" class="removeFieldButton">Remove</button>
            `;
            dynamicFromFields.appendChild(newField);

            newField.querySelector('.removeFieldButton').addEventListener('click', function() {
                dynamicFromFields.removeChild(newField);
            });
        });
    }

    const addWhereFieldButton = document.getElementById('addWhereFieldButton');
    if (addWhereFieldButton) {
        addWhereFieldButton.addEventListener('click', function() {
            const dynamicWhereFields = document.getElementById('dynamicWhereFields');
            const newField = document.createElement('div');
            newField.innerHTML = `
                <select name="dynamicWhereLogic[]">
                    <option value="AND">AND</option>
                    <option value="OR">OR</option>
                </select>
                <input type="text" name="dynamicWhereField[]" placeholder="Where Field" />
                <select name="dynamicWhereRelation[]">
                    <option value="=">=</option>
                    <option value="LIKE">LIKE</option>
                </select>
                <input type="text" name="dynamicWhereValue[]" placeholder="Value" />
                <button type="button" class="removeFieldButton">Remove</button>
            `;
            dynamicWhereFields.appendChild(newField);

            newField.querySelector('.removeFieldButton').addEventListener('click', function() {
                dynamicWhereFields.removeChild(newField);
            });
        });
    }
});

function showSQLQuery() {
    const selectedOption = document.getElementById('selectedOption').value;
    const titleFieldType = document.getElementById('titleFieldType').value;
    const title = document.getElementById('title').value;
    const as = document.getElementById('as').value;
    const asValue = document.getElementById('asValue').value;
    const content = document.getElementById('content').value;
    const whereField = document.getElementById('whereField').value;
    const whereRelation = document.getElementById('whereRelation').value;
    const whereValue = document.getElementById('whereValue').value;
    const groupBy = document.getElementById('groupBy').value;
    const groupByValue = document.getElementById('groupByValue').value;
    const orderByFieldType = document.getElementById('orderByFieldType').value;
    const orderBy = document.getElementById('orderBy').value;
    const orderByValue = document.getElementById('orderByValue').value;
    const dynamicTitleFields = Array.from(document.querySelectorAll('input[name="dynamicTitleField[]"]')).map(input => input.value);
    const dynamicTitleFieldTypes = Array.from(document.querySelectorAll('select[name="dynamicTitleFieldType[]"]')).map(select => select.value);
    const dynamicAs = Array.from(document.querySelectorAll('select[name="dynamicAs[]"]')).map(select => select.value);
    const dynamicAsValues = Array.from(document.querySelectorAll('input[name="dynamicAsValue[]"]')).map(input => input.value);
    const dynamicFromFields = Array.from(document.querySelectorAll('input[name="dynamicFromField[]"]')).map(input => input.value);
    const dynamicFromValues = Array.from(document.querySelectorAll('input[name="dynamicFromValue[]"]')).map(input => input.value);
    const dynamicWhereLogics = Array.from(document.querySelectorAll('select[name="dynamicWhereLogic[]"]')).map(select => select.value);
    const dynamicWhereFields = Array.from(document.querySelectorAll('input[name="dynamicWhereField[]"]')).map(input => input.value);
    const dynamicWhereRelations = Array.from(document.querySelectorAll('select[name="dynamicWhereRelation[]"]')).map(select => select.value);
    const dynamicWhereValues = Array.from(document.querySelectorAll('input[name="dynamicWhereValue[]"]')).map(input => input.value);

    let sql = '';
    if (selectedOption === 'SELECT') {
        sql = `SELECT ${titleFieldType !== 'none' ? `${titleFieldType.toUpperCase()}(${title})` : title}`;
        if (as === 'as' && asValue) {
            sql += ` AS ${asValue}`;
        }
        dynamicTitleFields.forEach((field, index) => {
            if (dynamicTitleFieldTypes[index] !== 'none') {
                sql += `, ${dynamicTitleFieldTypes[index].toUpperCase()}(${field})`;
            } else {
                sql += `, ${field}`;
            }
            if (dynamicAs[index] === 'as' && dynamicAsValues[index]) {
                sql += ` AS ${dynamicAsValues[index]}`;
            }
        });
        sql += ` FROM ${content}`;
        dynamicFromFields.forEach(field => {
            sql += `, ${field}`;
        });
        if (whereField || dynamicWhereFields.length) {
            sql += ` WHERE ${whereField} ${whereRelation} '${whereRelation === 'LIKE' ? `%${whereValue}%` : whereValue}'`;
            dynamicWhereFields.forEach((field, index) => {
                sql += ` ${dynamicWhereLogics[index]} ${field} ${dynamicWhereRelations[index]} '${dynamicWhereRelations[index] === 'LIKE' ? `%${dynamicWhereValues[index]}%` : dynamicWhereValues[index]}'`;
            });
        }
        if (groupBy !== 'none' && groupByValue) {
            sql += ` GROUP BY ${groupByValue}`;
        }
        if (orderBy !== 'none' && orderByValue) {
            sql += ` ORDER BY ${orderByFieldType !== 'none' ? `${orderByFieldType.toUpperCase()}(${orderByValue})` : orderByValue} ${orderBy.toUpperCase()}`;
        }
    } else if (selectedOption === 'INSERT') {
        const columns = [whereField, ...dynamicFromFields].join(',');
        const values = content.split(',').map(value => `'${value.trim()}'`).join(',');
        sql = `INSERT INTO ${title} (${columns}) VALUES (${values})`;
    } else if (selectedOption === 'UPDATE') {
        sql = `UPDATE ${title} SET ${content}`;
        dynamicFromFields.forEach((field, index) => {
            sql += `, ${field} = '${dynamicFromValues[index]}'`;
        });
        sql += ` WHERE ${whereField} ${whereRelation} '${whereValue}'`;
        dynamicWhereFields.forEach((field, index) => {
            sql += ` ${dynamicWhereLogics[index]} ${field} ${dynamicWhereRelations[index]} '${dynamicWhereRelations[index] === 'LIKE' ? `%${dynamicWhereValues[index]}%` : dynamicWhereValues[index]}'`;
        });
    } else if (selectedOption === 'DELETE') {
        sql = `DELETE FROM ${title} WHERE ${whereField} ${whereRelation} '${whereValue}'`;
        dynamicWhereFields.forEach((field, index) => {
            sql += ` ${dynamicWhereLogics[index]} ${field} ${dynamicWhereRelations[index]} '${dynamicWhereRelations[index] === 'LIKE' ? `%${dynamicWhereValues[index]}%` : dynamicWhereValues[index]}'`;
        });
    }

    // Display the SQL command with the actual values
    let displaySql = sql;
    if (selectedOption === 'INSERT') {
        const valuesArray = content.split(',').map(value => value.trim());
        valuesArray.forEach(value => {
            displaySql = displaySql.replace('?', `'${value}'`);
        });
    }

    alert(displaySql);
    return true;
}