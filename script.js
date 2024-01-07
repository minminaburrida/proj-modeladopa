document.addEventListener("DOMContentLoaded", function () {
  const btnEditar = document.createElement('button');
  btnEditar.classList.add('btn', 'btn-primary');

  // Aquí añade el 'eventListener' al botón
  btnEditar.addEventListener('click', () => {
    editar(habitacion.Id);
  });
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