function loginButton() {
    console.log("loginButton");
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    if (username == "" || password == "") {
        alert("Please enter username and password");
        return;
    }
    else if (password == "pass") {
        if (username == "Customer") {
            window.location.href = "Customer.html";
        }
        else if (username == "Staff") {
            window.location.href = "Staff.html";
        }
        else if (username == "Manager") {
            window.location.href = "Manager.html";
        }
        else if (username == "CEO") {
            window.location.href = "CEO.html";
        }
        else if (username == "kermit" || password == "kermit") {
            alert("bruh what? Kermit? Are you actually kidding me? no thats it im literally taking away your privileges");
            alert("redirecting to youareanidiot.com");
            alert("jk");
        }   
    }
    else {
        alert("Invalid username or password");
    }

    console.log(username);
}







// this updates the variable for whatever query type you select
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.options input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const selectedOption = this.nextElementSibling.getAttribute('data-txt');
            document.getElementById('selectedOption').value = selectedOption;

            const titleLabel = document.getElementById('titleLabel');
            const contentLabel = document.getElementById('contentLabel');
            const extraParams = document.getElementById('extraParams');

            if (selectedOption === 'SELECT') {
                titleLabel.textContent = 'SELECT: ';
                contentLabel.textContent = 'FROM: ';
                extraParams.style.display = 'block';
            } else if (selectedOption === 'INSERT') {
                titleLabel.textContent = 'INSERT INTO: ';
                contentLabel.textContent = 'VALUES: ';
                extraParams.style.display = 'none';
            } else if (selectedOption === 'UPDATE') {
                titleLabel.textContent = 'UPDATE: ';
                contentLabel.textContent = 'SET: ';
                extraParams.style.display = 'none';
            } else if (selectedOption === 'DELETE') {
                titleLabel.textContent = 'DELETE FROM: ';
                contentLabel.textContent = 'WHERE: ';
                extraParams.style.display = 'none';
            } else {
                titleLabel.textContent = 'Title';
                contentLabel.textContent = 'Content';
                extraParams.style.display = 'none';
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
                <label>Title Field:</label>
                <select name="dynamicTitleFieldType[]">
                    <option value="none">None</option>
                    <option value="max">MAX</option>
                    <option value="min">MIN</option>
                    <option value="sum">SUM</option>
                    <option value="avg">AVG</option>
                </select>
                <input type="text" name="dynamicTitleField[]" placeholder="Title Field" style="display: none;" />
                <button type="button" class="removeFieldButton">Remove</button>
            `;
            dynamicTitleFields.appendChild(newField);

            const fieldTypeSelect = newField.querySelector('select[name="dynamicTitleFieldType[]"]');
            const fieldInput = newField.querySelector('input[name="dynamicTitleField[]"]');
            fieldTypeSelect.addEventListener('change', function() {
                if (this.value !== 'none') {
                    fieldInput.style.display = 'inline';
                } else {
                    fieldInput.style.display = 'none';
                }
            });

            newField.querySelector('.removeFieldButton').addEventListener('click', function() {
                dynamicTitleFields.removeChild(newField);
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
    const title = document.getElementById('title').value;
    const as = document.getElementById('as').value;
    const asValue = document.getElementById('asValue').value;
    const content = document.getElementById('content').value;
    const whereField = document.getElementById('whereField').value;
    const whereRelation = document.getElementById('whereRelation').value;
    const whereValue = document.getElementById('whereValue').value;
    const dynamicTitleFields = Array.from(document.querySelectorAll('input[name="dynamicTitleField[]"]')).map(input => input.value);
    const dynamicTitleFieldTypes = Array.from(document.querySelectorAll('select[name="dynamicTitleFieldType[]"]')).map(select => select.value);
    const dynamicWhereLogics = Array.from(document.querySelectorAll('select[name="dynamicWhereLogic[]"]')).map(select => select.value);
    const dynamicWhereFields = Array.from(document.querySelectorAll('input[name="dynamicWhereField[]"]')).map(input => input.value);
    const dynamicWhereRelations = Array.from(document.querySelectorAll('select[name="dynamicWhereRelation[]"]')).map(select => select.value);
    const dynamicWhereValues = Array.from(document.querySelectorAll('input[name="dynamicWhereValue[]"]')).map(input => input.value);

    let sql = '';
    if (selectedOption === 'SELECT') {
        sql = `SELECT ${title}`;
        if (as === 'as' && asValue) {
            sql += ` AS ${asValue}`;
        }
        dynamicTitleFields.forEach((field, index) => {
            if (dynamicTitleFieldTypes[index] !== 'none') {
                sql += `, ${dynamicTitleFieldTypes[index].toUpperCase()}(${field})`;
            } else {
                sql += `, ${field}`;
            }
        });
        sql += ` FROM ${content}`;
        if (whereField || dynamicWhereFields.length) {
            sql += ` WHERE ${whereField} ${whereRelation} '${whereValue}'`;
            dynamicWhereFields.forEach((field, index) => {
                sql += ` ${dynamicWhereLogics[index]} ${field} ${dynamicWhereRelations[index]} '${dynamicWhereValues[index]}'`;
            });
        }
    } else if (selectedOption === 'INSERT') {
        sql = `INSERT INTO ${title} (${content}) VALUES (${whereField})`;
    } else if (selectedOption === 'UPDATE') {
        sql = `UPDATE ${title} SET ${content} = '${whereField}'`;
    } else if (selectedOption === 'DELETE') {
        sql = `DELETE FROM ${title} WHERE ${content} = '${whereField}'`;
    }

    alert(sql);
    return true;
}