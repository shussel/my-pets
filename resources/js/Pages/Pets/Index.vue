<script setup>
const props = defineProps({
    data: {
        type: Object,
    },
});
</script>

<template>
    <div>
        <table class="table-fixed mx-auto">
            <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 w-20">Image</th>
                <th class="px-4 py-2 w-20">Name</th>
                <th class="px-4 py-2">Species</th>
                <th class="px-4 py-2">Sex</th>
                <th class="px-4 py-2">Weight</th>
                <th class="px-4 py-2">Age</th>
                <th class="px-4 py-2">Birthday</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="pet in data.pets">
                <td class="border px-4 py-2"><img class="rounded-full" :src="pet.image + '?t=' + Math.ceil(Math.random()*10000)" :alt="pet.species" /></td>
                <td class="border px-4 py-2">{{ pet.name }}</td>
                <td class="border px-4 py-2">{{ pet.species }}</td>
                <td class="border px-4 py-2">{{ pet.sex }}</td>
                <td class="border px-4 py-2">{{ pet.weight }} lbs</td>
                <td class="border px-4 py-2">{{ pet.age }}</td>
                <td class="border px-4 py-2">{{ new Date(pet.birth_date).toLocaleDateString('en-us', { year:"numeric", month:"short", day:"numeric"}) }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>



