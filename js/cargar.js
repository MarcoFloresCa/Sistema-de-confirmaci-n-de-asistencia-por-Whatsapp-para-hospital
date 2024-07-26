let datatable;
let datatableInitialized = false;

const dataOpcion = {
  columnDefs: [
    {
      defaultContent: "",
      targets: "_all",
    },
  ],
  destroy: true,
  language: {
    lengthMenu: "Mostrar _MENU_ registros por página",
    zeroRecords: "Ningún usuario encontrado",
    info: "Mostrando de _START_ a _END_ de un total de _TOTAL_ registros",
    infoEmpty: "Ningún usuario encontrado",
    infoFiltered: "(filtrados desde _MAX_ registros totales)",
    search: "Buscar:",
    loadingRecords: "Cargando...",
    paginate: {
      first: "Primero",
      last: "Último",
      next: "Siguiente",
      previous: "Anterior",
    },
  },
};

const initDatatable = async () => {
  if (datatableInitialized) {
    datatable.destroy();
  }

  datatable = $("#tabla_pacientes").DataTable(dataOpcion);
  datatableInitialized = true;
};

function cargarExcel() {
  const input = document.getElementById("excelFile");
  const tabla = $("#tabla_pacientes").DataTable();
  const filasOmitidas = [];
  let primeraFila = true; // Variable de control para omitir la primera fila

  const file = input.files[0];
  if (file) {
    const reader = new FileReader();

    reader.onload = function (e) {
      const data = new Uint8Array(e.target.result);
      const workbook = XLSX.read(data, { type: "array" });

      const sheet = workbook.Sheets[workbook.SheetNames[0]];
      const dataObjects = XLSX.utils.sheet_to_json(sheet, { header: 1 });

      tabla.clear().draw();

      const columnIndex = 1;

      const filteredData = dataObjects.filter((row) => {
        if (primeraFila) {
          // Si es la primera fila (encabezado), no la validamos
          primeraFila = false;
          return false;
        }

        const cellValue = row[columnIndex].toString().trim();
        if (cellValue.length !== 8) {
          guardarDatosIncompletos(row);
          filasOmitidas.push(row);
          return false;
        }

        return true;
      });

      tabla.rows.add(filteredData).draw();

      document.getElementById("guardarDatosBtn").addEventListener("click", function () {
          const dataToSave = tabla.rows().data().toArray(); // Obtén los datos de la tabla
          guardarDatosEnBaseDeDatos(dataToSave);
      });

      if (filasOmitidas.length > 0) {
        mostrarFilasOmitidas(filasOmitidas);
      }
    };

    reader.readAsArrayBuffer(file);
  } else Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Seleccione un archivo de Excel valido",
    });
}

function mostrarFilasOmitidas(filas) {
  filas.sort((filaA, filaB) => {
    const numeroA = filaA[1];
    const numeroB = filaB[1];
    return numeroA - numeroB; 
  });
  const filasOmitidasList = $("#filasOmitidasList");
  filasOmitidasList.empty();

  const printWindow = window.open("", "", "width=600,height=600");
  printWindow.document.open();
  printWindow.document.write("<html><head><title></title></head><body>");
  printWindow.document.write(
    "<h1>Pacientes con número mal ingresado o no ingresado</h1>"
  );


  filas.forEach(function (fila, index) {
    const cellValue = fila[1].toString().trim();
    const cellValue1 = fila[2];
    const cellValue2 = fila[3];
    const cellValue3 = fila[4];
    const cellValue4 = fila[5];

    const value1 = cellValue.length === 0 ? "{sin número}" : cellValue;
    const listItem = `<li>Fila ${index + 1 } Numero: ${value1} ---Nombre: ${cellValue1} ---Rut: ${cellValue2} ---Dia: ${cellValue3} ---Hora: ${cellValue4} </li>`;
    filasOmitidasList.append(listItem);
    printWindow.document.write(
      `<p> Numero: ${value1}, Nombre: ${cellValue1}, Rut: ${cellValue2}, Dia: ${cellValue3}, Hora: ${cellValue4}</p>`
    );
  });

  printWindow.document.write("</body></html>");
  printWindow.document.close();

  printWindow.print();
  printWindow.close();
}

window.addEventListener("load", async () => {
  await initDatatable();
});

function guardarDatosEnBaseDeDatos(data) {
  $.ajax({
    type: "POST",
    url: "../php/cargabd.php", 
    data: {
      data: JSON.stringify(data),
    }, 
    success: function (response) {
      if (response === "success") {
        alert("Los datos se guardaron con éxito en la base de datos.");
        

        data.forEach(row => {
          const rut = row[3];
          eliminarDatosIncompletos(rut);
        });
      } else {
        alert(response);
      }
    },
    error: function () {
      alert("Error de comunicación con el servidor.");
    },
  });
}

function eliminarDatosIncompletos(rut) {
  $.ajax({
    type: "POST",
    url: "../php/eliminar_incompletos.php",
    data: {
      rut: rut
    },
    success: function (response) {
      console.log(response);
    },
    error: function () {
      console.error("Error al eliminar datos incompletos");
    },
  });
}


function guardarDatosIncompletos(row) {
    const rut = row[3];
    const nombre = row[2];
    const tipoError = "Número de teléfono incorrecto";
    const fechaRegistro = new Date().toISOString().slice(0, 19).replace('T', ' ');

    $.ajax({
        type: "POST",
        url: "../php/guardar_incompletos.php", 
        data: {
            rut: rut,
            nombre: nombre,
            tipo_error: tipoError,
            fecha_registro: fechaRegistro
        },
        success: function (response) {
            console.log(response);
        },
        error: function () {
            console.error("Error al guardar datos incompletos");
        },
    });
}