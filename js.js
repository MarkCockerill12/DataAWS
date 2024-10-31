function loginButton() {
    console.log("loginButton");
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    if (username == "" || password == "") {
        alert("Please enter username and password");
        return;
    }
    else if (username == "admin" && password == "admin") {
        alert("Login successful");
        window.location.href = "admin.html";
    }
    else if (username == "user" && password == "user") {
        alert("Login successful");
        window.location.href = "user.html";
    }
    else {
        alert("Invalid username or password");
    }

    console.log(username);
}