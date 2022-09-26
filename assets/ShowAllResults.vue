<template>
        <div>
            <h2>Welcome home</h2>
            <button @click="counter++">Click me {{counter}} </button>
        </div>
</template>
    <!--
    <div class="content">
        <table>
            <tr>
                <th>{{ lol }}</th>
                <th>Nr meczu</th>
                <th>Data Meczu</th>
                <th>Typ Meczu</th>
                <th>Liczba Żetonów</th>
                <th>Liczba Punktów</th>
                <th>Liczba Piw</th>
                <th></th>
            </tr>


            <tr :key ="match.matchId" v-for ="(match, index) in matches">
                <td>index</td>
                <td>{{ match.matchNr }}</td>
                <td>{{ match.dateOfMatch }}</td>
                <td @click="getUsersToMatch(matchId )"> Informacje o meczu</td>
            </tr>
        </table>

    </div>
</template>!-->

<script>
export default {
    name: "ShowAllResults",
    data: ()=> ({
        counter: 2
    })
}
</script>

<style scoped>

</style>