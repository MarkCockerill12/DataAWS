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