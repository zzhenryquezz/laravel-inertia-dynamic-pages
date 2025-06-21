<script setup lang="ts">
import { debounce } from 'lodash-es'

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    path: {
        type: String,
        default: null,
    },
})

const search = ref('')
const loading = ref(false)
const options = ref([])

async function load() {
    loading.value = true

    const [error, response] = await tryCatch(() => $fetch(props.path, {
        type: 'json',
        headers: {
            'Accept': 'application/json',
        },
        query: {
            search: search.value,
        },
    }))

    if (error) {
        loading.value = false
        $toast.error($t('error_occurred'))
        return
    }

    options.value = response.items

    setTimeout(() => {
        loading.value = false
    }, 1000)
}


watch(search, debounce(load, 1000), {
    immediate: true,
})


</script>

<template>
    <c-form-autocomplete
        v-model:search="search"
        :name
        :options
        :loading
    >
        <template
            v-for="(_, s) in $slots"
            #[s]="slotProps"
        >
            <slot
                :name="s"
                v-bind="slotProps"
            />
        </template>
    </c-form-autocomplete>
</template>
