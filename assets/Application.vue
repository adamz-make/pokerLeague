<template>
    <div>
        <h2>Welcome home</h2>
        <button @click="counter++">Click me {{counter}} </button>
    </div>
</template>

<script>
export default {
    name: "Application",
    data:() => ({
        counter: 0
    })
}
</script>

<style scoped>

</style>