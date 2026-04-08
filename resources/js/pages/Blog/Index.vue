<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { dashboard } from '@/routes';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import blog from '@/routes/blog';

const props = defineProps({
    blogs: {
        type: Object,
        required: true
    }
})

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
            {
                title: 'Blog',
                href: '#'
            }
        ],
    },
});
</script>

<template>

    <Head title="Dashboard" />

    <div class="m-5">

        <!-- Blog Form -->
        <Card class="p-4">
            <Form v-bind="blog.store()" :reset-on-success="['url']" v-slot="{ errors, processing }" class="flex flex-col gap-6">
                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Label for="url">Website URL <sup class="text-rose-800">*</sup></Label>
                        <Input id="url" type="text" name="url" required autofocus :tabindex="1" autocomplete="url"
                            placeholder="https://example.com" />
                        <InputError :message="errors.url" />
                    </div>

                    <div class="flex justify-between items-center gap-6">
                        <div>
                            <span class="text-xs text-muted-foreground" data-test="fetching-content" v-show="processing">
                                <div class="flex gap-2">
                                    <span>
                                        Fetching content from the url...
                                    </span>
                                    <Spinner />
                                </div>
                            </span>
                        </div>
                        <Button type="submit" :tabindex="4" :disabled="processing" data-test="login-button">
                            <Spinner v-if="processing" />
                            Process
                        </Button>
                    </div>
                </div>
            </Form>
        </Card>

        <!-- Blog Content -->
        <div v-if="props.blogs.length > 0">
            <Card class="p-4 mt-4" v-for="blog in props.blogs" :key="blog.id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <Link href="#">
                        <Card class="flex items-start justify-between gap-4 p-4">
                            <div class="flex-1">
                                <CardHeader class="pb-2">
                                    <CardTitle>{{ blog.title }}</CardTitle>
                                    <CardDescription>{{ blog.description }}</CardDescription>
                                </CardHeader>
                                <CardContent class="pb-2">
                                    <p>
                                        {{ $helpers.wordLimit(blog.content, 20) }}
                                    </p>
                                </CardContent>
                            </div>
                        </Card>
                    </Link>
                </div>
            </Card>
        </div>
        <div v-else>
            <!-- Card if Blog is empty -->
            <Card class="p-4 mt-4">
                <CardContent>
                    <p class="text-muted-foreground">
                        No blog posts found.
                    </p>
                </CardContent>
            </Card>
        </div>

    </div>
</template>
