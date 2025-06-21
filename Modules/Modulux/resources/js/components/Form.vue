<script lang="ts">
export interface FormField {
    name: string;
    label: string;
    value?: any; // initial value
    component: string; // e.g. 'c-form-text-field', 'c-form-select', etc.
    props?: Record<string, any>; // props to pass to the component
    rules?: string; // Laravel-style validation rules
}

export interface FormBack {
    label?: string;
    href: string;
}

</script>
<script setup lang="ts">

import { router } from '@inertiajs/vue3';
import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'
import Card from '@/components/ui/card/Card.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import { defineAsyncComponent } from 'vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import CardDescription from '@/components/ui/card/CardDescription.vue';
import MButton from './MButton.vue';
import { $t } from '../utils/lang';

const props = defineProps({
    title: {
        type: String,
        default: '',
    },
    description: {
        type: String,
        default: '',
    },
    fields: {
        type: Array as () => FormField[],
        default: () => [],
    },
    action: {
        type: String,
        default: '/tickets',
    },
    method: {
        type: String,
        default: 'POST',
    },
    submitText: {
        type: String,
        default: '',
    },
    back: {
        type: Object as () => FormBack | null,
        default: null,
    },
})

// components 
const components: Record<string, any> = {
    'select': defineAsyncComponent(() => import('./FormSelect.vue')),
    'text-field': defineAsyncComponent(() => import('./FormTextField.vue')),
    'textarea': defineAsyncComponent(() => import('./FormTextarea.vue')),
    'autocomplete': defineAsyncComponent(() => import('./FormAutocomplete.vue')),
}



// fields resolved 

const fieldsResolved = props.fields.map(field => {
    return {
        ...field,
        component: components[field.component] || field.component,
    };
});

function laravelRulesToZod(rules?: string) {
    if (!rules) {
        return z.any();
    }

    let zodSchema: any = null;

    const ruleList = rules.split('|');

    for (const rule of ruleList) {
        const [name, param] = rule.split(':');
        
        if (name === 'required') {
            continue; // 'required' is handled by zodSchema.required() later
        }

        // main types
        if (name === 'boolean') {
            zodSchema = z.boolean();
            continue
        }

        if (name === 'string') {
            zodSchema = z.string();
            continue
        }

        if (name === 'integer') {
            zodSchema = z.number().int();
            continue
        }

        if (name === 'email') {
            zodSchema = z.string().email();
            continue
        }

        // modifiers
        if (name === 'min' && zodSchema?.min) {
            zodSchema = zodSchema.min(Number(param));
            continue
        }

        if (name === 'max' && zodSchema?.max) {
            zodSchema = zodSchema.max(Number(param));
            continue
        }

        if (name === 'nullable' && zodSchema?.nullable) {
            zodSchema = zodSchema.nullable();
            continue
        }

        

        if (name === 'url' && zodSchema?.url) {
            zodSchema = zodSchema.url();
            continue
        }

        console.warn(`Could not handle rule: ${name}`);
    }

    // handle 'required' rule
    if (zodSchema && !ruleList.includes('required')) {
        zodSchema = zodSchema.optional();
    }

    if (!zodSchema) {
        return z.any();
    }

    return zodSchema;
}

const schema = toTypedSchema(
    z.object(
        Object.fromEntries(props.fields.map(field => [field.name, laravelRulesToZod(field.rules)]))
    )
);

const initialValues = Object.fromEntries(
    props.fields
        .filter(field => !!field.value)
        .map(field => [field.name,field.value])
);

const form = useForm({
    validationSchema: schema,
    initialValues: initialValues,
})

const onSubmit = form.handleSubmit((payload: any) => {
    const method = props.method.toLowerCase();

    if (method === 'patch') {
        router.patch(props.action, payload, {
            onError: () => {
                // $toast.error($t('formHasErrors'));
            }
        })
        return;
    }
    if (method === 'put') {
        router.put(props.action, payload, {
            onError: () => {
                // $toast.error($t('formHasErrors'));
            }
        })
        return;
    }
    if (method === 'delete') {
        router.delete(props.action, {
            data: payload,
            onError: () => {
                // $toast.error($t('formHasErrors'));
            }
        })
        return;
    }
    // Default to post
    router.post(props.action, payload, {
        onError: () => {
            // $toast.error($t('formHasErrors'));
        }
    })
})
</script>

<template>
    <Card>
        <CardHeader v-if="props.title || props.description">
            <CardTitle v-if="props.title">
                {{ props.title }}
            </CardTitle>
            <CardDescription v-if="props.description">
                {{ props.description }}
            </CardDescription>
        </CardHeader>

        <CardContent>
            <form
                class="w-full space-y-6"
                @submit="onSubmit"
            >
                <template
                    v-for="field in fieldsResolved"
                    :key="field.name"
                >
                    <component
                        :is="field.component"
                        :name="field.name"
                        :label="field.label"
                        v-bind="field.props"
                    />
                </template>
                <div class="flex justify-end gap-2">
                    <MButton
                        v-if="back"
                        type="button"
                        variant="outline"
                        :href="back.href"
                    >
                        {{ back.label || $t('back') }}
                    </MButton>
                    <MButton
                        size="sm"
                        type="submit"
                    >
                        {{ submitText || $t('submit') }}
                    </MButton>
                </div>
            </form>
        </CardContent>
    </Card>
</template>
