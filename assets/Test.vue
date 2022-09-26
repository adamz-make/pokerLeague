<template>
    <div>
        <h2>Welcome home</h2>
        <button @click="counter++">Click me {{counter}} </button>
    </div>
</template>

<script>
export default {
    name: "Test",
    data: ()=> ({
        counter: 13
    })
}
</script>

<style scoped>

</style>