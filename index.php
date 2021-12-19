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

            <td v-for="pregunta in preguntas">
                <div style="display: flex;">
                    <div class="star" @click="contestar(pregunta, 1, unidad)">
                    </div>
                    <div class="star" @click="contestar(pregunta, 2, unidad)">
                    </div>
                    <div class="star" @click="contestar(pregunta, 3, unidad)">
                    </div>
                    <div class="star" @click="contestar(pregunta, 4, unidad)">
                    </div>
                    <div class="star" @click="contestar(pregunta, 5, unidad)">
                    </div>
                    {{obtenerPuntaje(pregunta, unidad)}}
                </div>
            </td>

        </tr>
    </table>
 
    <br>
    <div class="input-field col s12">
        <textarea v-model="comentario" class="materialize-textarea"></textarea>
        <label for="textarea1">Comentarios</label>
    </div>
    <button class="btn" @click="guardar">Guardar</button>
</div>
<script>

    const app = new Vue({
        el: '#app',
        data: {
            unidades: [],
            preguntas: [],
            loading: false,
            comentario: '',
            respuestas: [],
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
            },
            contestar: function (pregunta, puntaje, unidad) {
                const item = { idPregunta: pregunta.id_pregunta, puntaje, idUnidad: unidad.id_unidad_aprendizaje };
                this.respuestas = this.respuestas.filter((r) => !(r.idPregunta === item.idPregunta && r.idUnidad === item.idUnidad))
                this.respuestas.push(item);
            },
            guardar: async function () {
              /*   if (this.respuestas.length !== this.preguntas.length * this.unidades.length) {
                    return;
                } */
                const idAlumno = 1; //TODO
                const data = {
                    idAlumno,
                    comentario: this.comentario,
                    respuestas: this.respuestas,
                }
                await axios.post('api.php/respuesta', data);
            },
            obtenerPuntaje: function (pregunta, unidad) {
                const res = this.respuestas.find((r) => r.idPregunta === pregunta.id_pregunta && r.idUnidad === unidad.id_unidad_aprendizaje);
                if (!res) {
                    return "*";
                }
                return res.puntaje;
            }
        }
    })
</script>



<?php include('footer.php'); ?>