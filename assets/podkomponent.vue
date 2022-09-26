<template>

    <div>
        <tr :key ="matchPlayer.id" v-for ="(matchPlayer, index) in matchPlayers">
            <td> Gracz: {{matchPlayer.user.login}}</td>
            <td>liczba żetonów: {{matchPlayer.tokens}}</td>
            <td>liczba punktów: {{matchPlayer.points}}</td>
            <td>liczba piw: {{matchPlayer.beers}}</td>
        </tr>
    </div>

</template>

<script>
export default {
    name: "podkomponent",
    props: ['matchPlayers'],
    created() {
        console.log(this.matchPlayers);

    }
}
</script>

<style scope>


</style>