
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="./styles.css" />
    <script src="script.js" defer></script>
    <title>Hotel Ibis</title>
  </head>
  <body>
    <!-- Top bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container">
        <a class="navbar-brand" href="#">
          <img

            class="logo"
            src="../assets/evie.webp"
            height="50px"
          /> Hotel De Evies
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-toggle="collapse"
          data-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <!-- Aquí van tus elementos de menú -->
            <li class="nav-item">
              <a class="nav-link active" href="#">Enlace 1</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Enlace 2</a>
            </li>
          </ul>
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="./login/" onclick="onLoginOrLogout()"
                >Iniciar Sesión</a
              >
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <script
      src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
      defer
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"
      defer
    ></script>
    <script
      src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
      defer
    ></script>
  </body>
</html>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles.css" />
    <title>Hotel Ibis</title>
  </head>
  <body>
    <div
      style="
        display: grid;
        place-items: center;
        width: 100%;
        height: 100vh;
        margin: 0;
      "
      class="login-container"
    >
      <div
        style="background: #fff; color: #000"
        class="login-container dinamico"
      >
        <div class="text-center">
          <h1>
            <b
              >Hotel Ibis
              <img
                style="position: relative; top: 10px"
                class="logo"
                src="../assets/evie.webp"
                height="50px"
              />
            </b>
          </h1>
        </div>
        <br /><br />
        <div class="wiwi">
          <form id="loginForm">
            <div class="form-group">
              <label for="username">Nombre de Usuario</label>
              <input
                type="text"
                id="username"
                name="username"
                onfocus="usernameFocused = true"
                onblur="usernameFocused = false"
                oninput="validateForm()"
                required
                onkeydown="checkEnter(event)"
              />
            </div>

            <div class="form-group">
              <label for="password">Contraseña</label>
              <input
                type="password"
                id="password"
                name="password"
                onfocus="passwordFocused = true"
                onblur="passwordFocused = false"
                oninput="validateForm()"
                required
                onkeydown="checkEnter(event)"
              />
            </div>
            <div class="text-center w-100">
              <br /><br />
              <button
                id="btnLogin"
                class="w-100"
                type="button"
                onclick="onSubmit()"
                disabled
                style="background: rgba(0, 0, 0, 0.3); color: #000"
              >
                Entrar
              </button>
              <br /><br />
              <a class="nav-link" href="/recuperar">¿Olvidó su contraseña?</a>
              <br /><br />
              <!-- <a class="nav-link" href="/crear-cuenta">CREAR CUENTA</a> -->
            </div>
          </form>
        </div>
      </div>
    </div>

    <script>
      function validateForm() {
        const username = document.getElementById("username").value;
        const password = document.getElementById("password").value;
        const btnLogin = document.getElementById("btnLogin");

        // Habilitar o deshabilitar el botón en función del contenido de los campos
        btnLogin.disabled = !(username && password);
        btnLogin.style.background =
          username && password ? "rgb(149 238 255)" : "rgba(0, 0, 0, 0.3)";
      }

      function onSubmit() {
        AuthService.login()
      }

      function AuthService() {
    this.apiURL = `https://m4r3s9vt-5192.usw3.devtunnels.ms/auth/`;
    this.userToken = null;
    this.refToken = null;
}

AuthService.prototype.validarToken = function (email, token) {
    return fetch(`${this.apiURL}validate_token/${email}/${token}`);
};

AuthService.prototype.verToken = function (email) {
    return fetch(`${this.apiURL}check_token/${email}`);
};

AuthService.prototype.currentUser = function (token) {
    return fetch(`${this.apiURL}users/current?token=${token}`);
};

AuthService.prototype.resetPassword = function (uid, token, data) {
    return fetch(`${this.apiURL}reset_password/${uid}/${token}/`, {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        }
    });
};

AuthService.prototype.recoveryPassword = function (data) {
    return fetch(`${this.apiURL}password/recovery/`, {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        }
    });
};

AuthService.prototype.changePassword = function (data) {
    return fetch(`${this.apiURL}password/change/`, {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        }
    });
};

AuthService.prototype.changePasswordToken = function (data) {
    return fetch(`${this.apiURL}password/change/token/`, {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        }
    });
};

AuthService.prototype.refreshToken = function () {
    const refresh_token = { refresh_token: this.refToken };
    return fetch(`${this.apiURL}token/refresh/`, {
        method: 'POST',
        body: JSON.stringify(refresh_token),
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(res => res.json())
        .then(data => {
            this.saveTokens(data);
            return data;
        });
};

AuthService.prototype.saveTokens = function (data) {
    this.userToken = data.access_token;
    this.refToken = data.refresh_token;
    const expire = new Date();
    expire.setSeconds(Number(data.expires_in));

    localStorage.setItem('token', String(this.userToken));
    localStorage.setItem('refresh_token', String(this.refToken));
    localStorage.setItem('expire', expire.getTime().toString());
};

AuthService.prototype.login = function (user) {
    return fetch(`${this.apiURL}login/`, {
        method: 'POST',
        body: JSON.stringify(user),
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(res => res.json())
        .then(data => {
            if (data.access_token) {
                this.saveTokens(data);
            }
            return data;
        });
};

AuthService.prototype.logout = function () {
    const token = { token: this.readToken() };
    return fetch(`${this.apiURL}logout/`, {
        method: 'POST',
        body: JSON.stringify(token),
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(res => res.json())
        .then(() => {
            this.clearToken();
        });
};

AuthService.prototype.readToken = function () {
    if (localStorage.getItem('token')) {
        this.userToken = localStorage.getItem('token');
        this.refToken = localStorage.getItem('refresh_token');
    } else {
        this.userToken = '';
        this.refToken = '';
    }
    return String(this.userToken);
};

AuthService.prototype.isAuth = function () {
    this.readToken();
    if (this.userToken.length < 15) {
        this.clearToken();
        return false;
    }
    const expire = Number(localStorage.getItem('expire'));
    const expireDate = new Date(expire);
    if (expireDate > new Date()) {
        this.refreshToken();
        return true;
    } else {
        return false;
    }
};

AuthService.prototype.clearToken = function () {
    localStorage.removeItem('token');
    localStorage.removeItem('refresh_token');
    localStorage.removeItem('expire');
    this.userToken = '';
    this.refToken = '';
};
      function checkEnter(event) {
        if (event.key === "Enter") {
          onSubmit();
        }
      }
    </script>
  </body>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const formGroups = document.querySelectorAll(".form-group");

      formGroups.forEach(function (formGroup) {
        const input = formGroup.querySelector("input");

        // Añadir evento focus
        input.addEventListener("focus", function () {
          formGroup.classList.add("focused");
        });

        // Añadir evento blur
        input.addEventListener("blur", function () {
          if (input.value === "") {
            formGroup.classList.remove("focused");
          }
        });
      });
    });
  </script>
</html>
