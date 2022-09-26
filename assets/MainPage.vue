<template>
    <div class="content">
        <podkomponent :lol = "lol"></podkomponent>
        <table>
            <tr>
                <th>Nr meczu</th>
                <th>Data Meczu</th>
                <th>Typ Meczu</th>
            </tr>
            <tr :key ="match.id" v-for ="(match, index) in decodeMatches">
                <td>{{ index }}</td>
                <td>{{ match.dateOfMatch }}</td>
                <td> {{ match.matchType}}</td>
                <td @click="showUsers(match.id)"> Informacje o meczu</td>
                <div :id =match.id style="display:none">
                    <podkomponent :matchPlayers = match.matchPlayers ></podkomponent>
                </div>
            </tr>

        </table>

    </div>

</template>

<script>
import podkomponent from "./podkomponent.vue";

export default {
    name: "Main-page",
    components: {
        podkomponent,
    },

    data:function () {
        return {
            test2: null,
            test: null,
            lol: 325,
            decodeMatches: null,
        }
    },
    created() {
        console.log(this.matches);
        this.decodeMatches = JSON.parse(this.matches);
    },
    props: ['matches'],

    methods: {
        sayHello: function(){
            return 'jaja jak berety';
        },
        showUsers: function (id) {
            let meczdiv = document.getElementById(id);
            window.alert(meczdiv);

            meczdiv.style.display = "inline";
        }
    }
}

</script>

<style scoped>

</style>