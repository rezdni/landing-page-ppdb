<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Create Account</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    </head>
    <body>
        <section class="d-flex align-items-center py-4 bg-body-tertiary" style="height: 100vh;">
            <div class="form-signin w-100 m-auto" style="max-width: 350px;">
                <form>
                    <img class="mb-4" src="assets/images/logo.png" alt="" width="72" height="57">
                    <h1 class="h3 mb-3 fw-normal">Register</h1>
                
                    <div class="form-floating">
                        <input type="text" class="form-control" id="floatingName" placeholder="Your name">
                        <label for="floatingName">Your name</label>
                    </div>
                    <div class="form-floating">
                        <input type="email" class="form-control" id="floatingEmail" placeholder="name@example.com">
                        <label for="floatingEmail">Email address</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
                        <label for="floatingPassword">Password</label>
                    </div>
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelect" aria-label="Floating label select role">
                            <option selected></option>
                            <option value="Admin">Admin</option>
                            <option value="Calon">Calon</option>
                        </select>
                        <label for="floatingSelect">User role</label>
                    </div>
                
                    <div class="form-check text-start my-3">
                        <input class="form-check-input" type="checkbox" value="show-pw" id="flexCheckDefault" onclick="showPassword(this)">
                        <label class="form-check-label" for="flexCheckDefault">Show password</label>
                    </div>
                    <button class="btn btn-primary w-100 py-2" type="submit" onclick="createAccount(this.parentElement, event)">Register</button>
                </form>
            </div>
        </section>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function showPassword(elm) {
                let passField = document.getElementById("floatingPassword");
                if (elm.checked) {
                    passField.setAttribute("type", "text");
                } else {
                    passField.setAttribute("type", "password");
                }
            }

            function createAccount(elm, event) {
                event.preventDefault();

                let name = elm.querySelector(".form-floating #floatingName").value;
                let email = elm.querySelector(".form-floating #floatingEmail").value;
                let password = elm.querySelector(".form-floating #floatingPassword").value;
                let role = elm.querySelector(".form-floating #floatingSelect").value;

                let xhr = new XMLHttpRequest();
                xhr.open("POST", "backend/kelola-pengguna.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onload = function () {
                    if (xhr.status === 200) {
                        console.log(xhr.responseText);
                        let respon = JSON.parse(xhr.responseText);
                        if (respon.status === "berhasil") {
                            alert("User added");
                        } else {
                            alert("Failed to add user");
                        }
                    } else {
                        alert("Failed to process data");
                    }
                }
                
                xhr.send(
                    "createUser=true&name=" +
                    encodeURIComponent(name) +
                    "&email=" +
                    encodeURIComponent(email) +
                    "&password=" +
                    encodeURIComponent(password) +
                    "&role=" +
                    encodeURIComponent(role)
                );
            }
        </script>
    </body>
</html>
