function loginButton() {
    console.log("loginButton");
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    if (username == "" || password == "") {
        alert("Please enter username and password");
        return;
    }
    else if (username == "staff" && password == "staff") {
        window.location.href = "staff.html";
    }
    else if (username == "shop" && password == "shop") {
        window.location.href = "shop.html";
    }
    else if (username == "manufacture" && password == "manufacture") {
        window.location.href = "manufacture.html";
    }
    else if (username == "timetable" && password == "timetable") {
        window.location.href = "timetable.html";
    }
    else if (username == "kermit" || password == "kermit") {
        alert("bruh what? Kermit? Are you actually kidding me? no thats it im literally taking away your privileges");
        alert("redirecting to youareanidiot.com");
        alert("jk");
    }   
    else {
        alert("Invalid username or password");
    }

    console.log(username);
}