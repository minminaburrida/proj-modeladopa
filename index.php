<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,700;1,600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css" />
  <link rel="stylesheet" href="bs.css" />
  <script src="script.js"></script>
  <title>Hotel Ibis</title>
</head>

<body>
  <!-- Top bar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="#">
        <img class="logo" src="assets/evie.webp" height="50px" /> Hotel De Evies
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
            <a class="nav-link" href="./login/" onclick="onLoginOrLogout()">Iniciar Sesión</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div id="login">
    <div style="
        display: grid;
        place-items: center;
        width: 100%;
        height: 100vh;
        margin: 0;
      " class="login-container">
      <div style="background: #fff; color: #000" class="login-container dinamico">
        <div class="text-center">
          <h1>
            <b>Hotel Ibis
              <img style="position: relative; top: 10px" class="logo" src="assets/evie.webp" height="50px" />
            </b>
          </h1>
        </div>
        <br /><br />
        <div class="wiwi">
          <form id="loginForm">
            <div class="form-group">
              <label for="username">Nombre de Usuario</label>
              <input type="text" id="username" name="username" onfocus="usernameFocused = true"
                onblur="usernameFocused = false" oninput="validateForm()" required onkeydown="checkEnter(event)" />
            </div>

            <div class="form-group">
              <label for="password">Contraseña</label>
              <input type="password" id="password" name="password" onfocus="passwordFocused = true"
                onblur="passwordFocused = false" oninput="validateForm()" required onkeydown="checkEnter(event)" />
            </div>
            <div class="text-center w-100">
              <br /><br />
              <button id="btnLogin" class="w-100" type="button" onclick="login()" disabled
                style="background: rgba(0, 0, 0, 0.3); color: #000">
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
  </div>

  <div id="menu" style="display: none;">
    <!-- Contenido para mostrar las habitaciones en una tabla -->
    <h2>Habitaciones Disponibles</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody id="habitacionesList"></tbody>
    </table>
  </div>
  <script>

    async function validateForm() {
      const username = document.getElementById("username").value;
      const password = document.getElementById("password").value;
      const btnLogin = document.getElementById("btnLogin");
      btnLogin.disabled = !(username && password);
      btnLogin.style.background =
        username && password ? "rgb(149 238 255)" : "rgba(0, 0, 0, 0.3)";
    }
    function checkEnter(event) {
      if (event.key === "Enter") {
        login();
      }
    }
    async function login() {
      const username = document.getElementById("username").value;
      const password = document.getElementById("password").value;

      const formData = new FormData();
      formData.append('Username', username);
      formData.append('Password', password);

      try {
        // Realizar la solicitud de inicio de sesión
        const loginResponse = await fetch('http://localhost:5192/auth/login', {
          method: 'POST',
          body: formData,
        });

        // Obtener los datos de la respuesta
        const loginData = await loginResponse

        // Verificar si el inicio de sesión fue exitoso
        if (loginData) {
          // Inicio de sesión exitoso, ocultar el formulario de inicio de sesión y mostrar las habitaciones
          hideLogin()

          await cargarHabitaciones();

        } else {
          // Manejar el caso en que el inicio de sesión no fue exitoso
          console.error('Inicio de sesión fallido:', loginData);
        }
      } catch (error) {
        // Manejar cualquier error que ocurra durante el proceso
        console.error('Error durante el inicio de sesión:', error);
      }
    }
    function hideLogin() {
      document.getElementById('login').style.display = 'none';
      document.getElementById('menu').style.display = 'block';
    }
    async function cargarHabitaciones() {
      // Realizar una solicitud para obtener las habitaciones
      const habitacionesResponse = await fetch('http://localhost:5192/habitaciones/');
      const habitacionesData = await habitacionesResponse.json();

      // Mostrar las habitaciones en la lista
      const habitacionesList = document.getElementById('habitacionesList');
      habitacionesList.innerHTML = ''; // Limpiar la lista antes de agregar nuevas habitaciones

      habitacionesData.forEach(habitacion => {

        const tr = document.createElement('tr');
        const _ID = document.createElement('td');
        _ID.textContent = '#' + habitacion.Id;

        const _Estado = document.createElement('td');

        const edit = document.createElement('td');
        _Estado.textContent = habitacion.Estado;

        const btnEditar = document.createElement('button')
        btnEditar.classList.add('btn', 'btn-primary');
        btnEditar.textContent = 'Editar'
        btnEditar.addEventListener('click', () => { editar(habitacion.Id) });
        // btnEditar.addEventListener('click', () => { guardar(habitacion.Id) });

        edit.appendChild(btnEditar);
        tr.appendChild(_ID); tr.appendChild(_Estado); tr.appendChild(edit);
        tr.id = 'habitacion_' + habitacion.Id;
        habitacionesList.appendChild(tr);
      });
    }
    async function editar(id) {
      console.log('Estás editando la habitación ' + id);

      // Obtén la habitación actual
      const room = document.querySelector('#habitacion_' + id);
      if (room.children[2].children[0].textContent === 'Editar') {
        room.children[2].children[0].textContent = 'Guardar'
        // Crear un elemento select con las opciones
        const selects = document.createElement('select');
        selects.innerHTML = '<option value="' + room.children[1].textContent + '" selected="true" disabled>Selecciona</option>\
        <option value="Ocupado">Ocupado</option>\
        <option value="Sucio">Sucio</option>\
        <option value="En Limpieza">En Limpieza</option>\
        <option value="Disponible">Disponible</option>';
        // selects.value=""

        // Asigna el valor actual como opción seleccionada
        const currentState = room.children[1].textContent.toLowerCase();
        selects.value = currentState;

        // Reemplaza el estado actual por el elemento select
        room.replaceChild(selects, room.children[1]);
      }
      else {
        room.children[2].children[0].textContent = 'Editar'
        // Obtén el valor seleccionado del select
        const selectedState = room.children[1].value;
        guardar({
          id:id,
          estado:selectedState
        })
        // Realiza cualquier acción necesaria con el nuevo estado
        console.log('Guardando nuevo estado:', selectedState);

        // Reemplaza el select por el estado actualizado
        const estado = document.createElement('td');
        estado.textContent = selectedState;
        room.replaceChild(estado, room.children[1]);
      }
    }
    async function guardar(room) {
      const formData = new FormData();
      formData.append('id', room.id);
      formData.append('estado', room.estado);
      try {
        // Realizar la solicitud de inicio de sesión
        const response = await fetch('http://localhost:5192/habitaciones/edit?id='+room.id+'&estado='+room.estado)

        // Obtener los datos de la respuesta
        const data = await response.json()

        // Verificar si el inicio de sesión fue exitoso
        if (data.status==="OK") {
          // Inicio de sesión exitoso, ocultar el formulario de inicio de sesión y mostrar las habitaciones
          console.log(data.message);
        }
        else{
          console.error('Andas valiendo pilin pa\nDetalle:', data.message);
        }
      } catch (error) {
        // Manejar cualquier error que ocurra durante el proceso
        console.error('Andas valiendo pilin pa, checa el backend\nDetalle:', error);
      }
    }

  </script>



</body>

</html>