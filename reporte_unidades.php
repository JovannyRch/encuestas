<?php include('header.php'); ?>
<div id="app">

    <div class="progress" v-if="loading">
        <div class="determinate" style="width: 70%"></div>
    </div>
    <div v-show="!loading">

        <h3 class="text-center">Reporte unidades</h3>

    <br><br>
        <ul class="collection">
            <li class="collection-item" v-for="unidad in unidades">
               <h5 class="text-center"> {{unidad.unidad.nombre}}</h5>

                <table>
                    <thead>
                        <th>Pregunta</th>
                        <th>Promedio</th>
                    </thead>
                    <tbody>
                        <tr v-for="pregunta in unidad.unidad.preguntas">
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
                console.log("data", data);
                this.unidades = data.unidades;
                console.log(this.unidades);
            },
        }
    });
</script>

<?php include('footer.php'); ?>