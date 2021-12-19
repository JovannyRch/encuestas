<?php include('header.php'); ?>
<div id="app">

    <div class="progress" v-if="loading">
        <div class="determinate" style="width: 70%"></div>
    </div>
    <div v-show="!loading">

        <h3 class="text-center">Reporte por unidad</h3>

        <br><br>

        <div class="input-field col s6 m6" @change="reporte">
            <select class="browser-default" v-model="idUnidadSeleccionada">
                <option value="-1" disabled selected>Seleccione una unidad</option>
                <option v-for="unidad in unidades" :value="unidad.id_unidad_aprendizaje">{{unidad.nombre}}</option>
            </select>
        </div>

        <br><br>



        <div v-show="idUnidadSeleccionada != -1">
            <div class="progress" v-show="loadingReporte">
                <div class="determinate" style="width: 70%"></div>
            </div>
            <div v-show="!loadingReporte">
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
            </div>
        </div>

    </div>

</div>
<script>
    const app = new Vue({
        el: '#app',
        data: {
            loading: false,
            loadingReporte: false,
            idUnidadSeleccionada: -1,
            preguntas: [],
            unidades: [],
            chart: null,
            
        },
        created: function() {
            this.getData();
        },
        methods: {
            getData: async function() {
                this.loading = true;
                await this.getUnidades();
                this.loading = false;
            },
            getUnidades: async function() {
                const {
                    data
                } = await axios('api.php/unidades');
                this.unidades = data;
            },
            reporte: async function() {
                const {
                    data
                } = await axios.post('api.php/reporte_unidad', {
                    idUnidad: this.idUnidadSeleccionada
                });

                this.preguntas = data.preguntas;
                this.generarGrafica();
            },
            generarGrafica: function() {

                if(this.chart != null){
                    this.chart.destroy();
                }
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
                this.chart = new Chart(
                    document.getElementById('chart-preguntas'),
                    config
                );
            }
        }
    });
</script>

<?php include('footer.php'); ?>