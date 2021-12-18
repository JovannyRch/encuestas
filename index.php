<?php include('header.php'); ?>




<div id="app">
    <h3 class="uppercase">Escuela superior de computo</h3>
    <p class="text-center">
        Estimada(o) estudiante, en la ESCOM queremos saber tu opinion sobre las clases que has tenido hasta el momento
        este
        semestre 2021-2022/1.
    </p>
    <p class="text-center">
        Te pedimos que te tomes tu tiempo para responder...
    </p>
    <div class="progress" v-if="loading">
        <div class="determinate" style="width: 70%"></div>
    </div>

    <table>
        <thead>
            <th>UA</th>
            <th v-for="pregunta in preguntas">
                {{pregunta.pregunta}}
            </th>
        </thead>
        <tr v-for="unidad in unidades">
            <td>
                {{unidad.nombre}}
            </td>

            <th v-for="pregunta in preguntas">
                <input >
            </th>

        </tr>
    </table>
</div>
<script>

    const app = new Vue({
        el: '#app',
        data: {
            unidades: [],
            preguntas: [],
            loading: false,
        },
        created: function () {
            this.getData();
        },
        methods: {
            getData: async function () {
                this.loading = true;
                await this.getPreguntas();
                await this.getUnidades();
                this.loading = false;
            },
            getPreguntas: async function () {
                const { data } = await axios('api.php/preguntas');
                this.preguntas = data;
            },
            getUnidades: async function () {
                const { data } = await axios('api.php/unidades');
                this.unidades = data;
            }
        }
    })
</script>



<?php include('footer.php'); ?>