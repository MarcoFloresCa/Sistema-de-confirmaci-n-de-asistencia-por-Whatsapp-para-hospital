<?php require_once "vistas/parte_superior.php" ?>

<!--INICIO del cont principal-->
<div class="container">
    <h1>Contenido principal</h1>


    <div class="container" style="display: flex;">
        <div class="row">
            <div class="col-lg-12">
            <input style="" type="file" id="excelFile" accept=".xls, .xlsx">
                <button style="margin-left: 4vh;" id="excelFile" type="button" class="btn btn-success" data-toggle="modal"
                    onclick="cargarExcel()">Cargar Excel</button>
            </div>
        </div>
        <div style="margin-left: 4vh;">
            <button class="btn btn-success" id="guardarDatosBtn">Guardar Datos </button>
        </div>
    </div>
    <br>
    <div class="col-md-12 text-center">
        <h1>Lista de Pacientes</h1>
      </div>
      <div class="col-md-12">
        <table id="tabla_pacientes" class="table table-striped" style="width:100%">
          <thead>
            <tr>
              <th scope="col" class="centered">Codigo</th>
              <th scope="col" class="centered">Numero</th>
              <th scope="col" class="centered">Nombre</th>
              <th scope="col" class="centered">Rut</th>
              <th scope="col" class="centered">Dia</th>
              <th scope="col" class="centered">Hora</th>
              <th scope="col" class="centered">Medico</th>
              <th scope="col" class="centered">Espacialidad</th>
              <th scope="col" class="centered">Tipo de Consulta</th>
              <th scope="col" class="centered">Lugar</th>
              <th scope="col" class="centered">Recinto</th>
              <th scope="col" class="centered">Email</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>

    <div class="modal fade" id="filasOmitidasModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Filas Omitidas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <ul id="filasOmitidasList"></ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
    <!--Modal para CRUD-->
    <div class="modal fade" id="modalCRUD" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btnGuardar" class="btn btn-dark">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<!--FIN del cont principal-->
<?php require_once "vistas/parte_inferior.php" ?>

