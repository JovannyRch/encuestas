<?php include('header.php'); ?>
<nav>
    <div class="nav-wrapper teal">
        <a href="/administracion.php" class="brand-logo"> Encuestas</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a href="administracion.php">Inicio</a></li>
            <li><a href="reporte_unidades.php">Reporte unidades de aprendizaje</a></li>
            <li><a href="reporte_unidad.php">Reporte por unidad de aprendizaje</a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
        </ul>
    </div>
</nav>
<div id="app" class="container">

    <div class="progress" v-if="loading">
        <div class="determinate" style="width: 70%"></div>
    </div>
    <div v-show="!loading">

        <h3 class="text-center">Reporte unidades</h3>

    <br><br>
        <ul class="collection">
            <li class="collection-item" v-for="unidad in unidades">
               <h5 class="text-center"> {{unidad.nombre}}</h5>

                <table>
                    <thead>
                        <th>Pregunta</th>
                        <th>Promedio</th>
                    </thead>
                    <tbody>
                        <tr v-for="pregunta in unidad.preguntas">
                            <td>
                                {{pregunta.pregunta.pregunta}}
                            </td>
                            <td>
                                {{pregunta.promedio}}
                            </td>
                        </tr>
                    </tbody>
                </table>

            <br><br>
            </li>
        </ul>


      

    </div>

</div>
<script>
    const app = new Vue({
        el: '#app',
        data: {
            loading: false,
            unidades: [],
        },
        created: function() {
            this.getData();
        },
        methods: {
            getData: async function() {
                this.loading = true;
                await this.reporte();
                this.loading = false;
            },
            reporte: async function() {
                const {
                    data
                } = await axios.get('api.php/reporte_unidades');
                this.unidades = data.unidades;
                console.log(this.unidades);
            },
        }
    });
</script>

<?php include('footer.php'); ?>