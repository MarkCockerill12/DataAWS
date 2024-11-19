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
document.querySelectorAll('.options input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const selectedOption = this.nextElementSibling.getAttribute('data-txt');
        document.getElementById('selectedOption').value = selectedOption;

        // Update labels and show/hide extra parameters based on the selected option
        const titleLabel = document.getElementById('titleLabel');
        const contentLabel = document.getElementById('contentLabel');
        const extraParams = document.getElementById('extraParams');

        if (selectedOption === 'SELECT') {
            titleLabel.textContent = 'Column';
            contentLabel.textContent = 'Table';
            extraParams.style.display = 'block';
        } else if (selectedOption === 'INSERT') {
            titleLabel.textContent = 'Table';
            contentLabel.textContent = 'Values';
            extraParams.style.display = 'none';
        } else if (selectedOption === 'UPDATE') {
            titleLabel.textContent = 'Table';
            contentLabel.textContent = 'Set';
            extraParams.style.display = 'none';
        } else if (selectedOption === 'DELETE') {
            titleLabel.textContent = 'Table';
            contentLabel.textContent = 'Condition';
            extraParams.style.display = 'none';
        } else {
            titleLabel.textContent = 'Title';
            contentLabel.textContent = 'Content';
            extraParams.style.display = 'none';
        }
    });
});

document.getElementById('groupBy').addEventListener('change', function() {
    const groupByValue = document.getElementById('groupByValue');
    if (this.value === 'groupBy') {
        groupByValue.style.display = 'block';
    } else {
        groupByValue.style.display = 'none';
    }
});

document.getElementById('minMax').addEventListener('change', function() {
    const minMaxValue = document.getElementById('minMaxValue');
    if (this.value === 'min' || this.value === 'max') {
        minMaxValue.style.display = 'block';
    } else {
        minMaxValue.style.display = 'none';
    }
});

document.getElementById('as').addEventListener('change', function() {
    const asValue = document.getElementById('asValue');
    if (this.value === 'as') {
        asValue.style.display = 'block';
    } else {
        asValue.style.display = 'none';
    }
});