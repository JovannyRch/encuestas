<?php include('header.php'); ?>
<div id="app">



    <div class="progress" v-if="loading">
        <div class="determinate" style="width: 70%"></div>
    </div>
    <div v-show="!loading">

        <h3 class="text-center">Reporte administración</h3>
        <div class="row">
            <div class="col s12 m6">

                <div class="card horizontal">

                    <div class="card-stacked">
                        <div class="card-content">
                            <h4>Total alumnos inscritos: {{totalAlumnos}}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col s12 m6">

                <div class="card horizontal">

                    <div class="card-stacked">
                        <div class="card-content">
                            <h4>Total encuestas contestadas: {{totalEncuestas}}</h4>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <table>
            <thead>
                <th>Pregunta</th>
                <th>Promedio</th>
            </thead>
            <tbody>
                <tr v-for="pregunta in preguntas">
                    <td>
                        {{pregunta.pregunta.pregunta}}
                    </td>
                    <td>
                        {{pregunta.promedio}}
                    </td>
                </tr>
            </tbody>
        </table>


        <div>
            <br>
            <canvas id="chart-preguntas"></canvas>
        </div>
        <div>
            <br>
            <h3>Todos los comentarios</h3>
            <div class="row">
                <div class="col s12 m6" v-for="comentario in comentarios">
                    <div class="card horizontal">
                        <div class="card-stacked">
                            <div class="card-content">

                                <p>
                                    {{comentario.comentario}}
                                </p>
                                <small> <i>{{`${comentario.nombre} ${comentario.apellido_paterno}
                                        ${comentario.apellido_materno}`}}</i></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
<script>
    const app = new Vue({
        el: '#app',
        data: {
            preguntas: [],
            loading: false,
            totalAlumnos: 0,
            totalEncuestas: 0,
            comentarios: [],
        },
        created: function () {
            this.getData();
        },
        methods: {
            getData: async function () {
                this.loading = true;
                await this.reporte();
                this.loading = false;
            },
            reporte: async function () {
                const { data } = await axios.get('api.php/reporte');
                this.totalAlumnos = data.totalAlumnos;
                this.totalEncuestas = data.totalEncuestas;
                this.preguntas = data.preguntas;
                this.comentarios = data.comentarios;
                this.generarGrafica();
            },
            generarGrafica: function () {

                const data = {
                    labels: this.preguntas.map((item) => item.pregunta.pregunta),
                    datasets: [{
                        label: 'Promedio por preguntas',
                        data: this.preguntas.map((item) => item.promedio),
                    }]
                };
                const config = {
                    type: 'bar',
                    data: data,
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    },
                };
                new Chart(
                    document.getElementById('chart-preguntas'),
                    config
                );
            }
        }
    });
</script>

<?php include('footer.php'); ?>