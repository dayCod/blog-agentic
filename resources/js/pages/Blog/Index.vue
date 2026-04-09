<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { dashboard } from '@/routes';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import blog from '@/routes/blog';
import { ref, watch } from 'vue';
import { useJobProgress } from '@/composables/useJobProgress';

const props = defineProps({
    blogs: {
        type: Object,
        required: true,
    },
    jobId: {
        type: String,
        default: null,
    },
});

const blogsState = ref(props.blogs);

const { progress, statusText, isProcessingJob, listenToJob } = useJobProgress();

const form = useForm({
    url: '',
});

const submitUrl = () => {
    form.post(blog.store().url, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (page) => {
            form.reset('url');
        },
        onError: () => {
            statusText.value = 'Gagal memproses URL';
            setTimeout(() => {
                statusText.value = '';
            }, 3000);
        },
    });
};

watch(
    () => props.jobId,
    (newJobId) => {
        if (newJobId) {
            listenToJob(newJobId, (channel) => {
                channel.listen('.BlogCreated', (e: { blog: any }) => {
                    blogsState.value.unshift(e.blog);
                });
            });
        }
    },
    { immediate: true },
);

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
            {
                title: 'Blog',
                href: '#',
            },
        ],
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <div class="m-5">
        <!-- Blog Form -->
        <Card class="p-4">
            <form @submit.prevent="submitUrl" class="flex flex-col gap-6">
                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Label for="url"
                            >Website URL
                            <sup class="text-rose-800">*</sup></Label
                        >
                        <Input
                            id="url"
                            type="text"
                            name="url"
                            v-model="form.url"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="url"
                            placeholder="https://example.com"
                        />
                        <InputError :message="form.errors.url" />
                    </div>

                    <div class="flex items-center justify-between gap-6">
                        <div>
                            <span
                                class="text-xs text-muted-foreground"
                                data-test="fetching-content"
                                v-show="
                                    form.processing ||
                                    isProcessingJob ||
                                    statusText
                                "
                            >
                                <div class="flex gap-2">
                                    <span>
                                        {{ statusText || 'Proses dimulai...' }}
                                    </span>
                                    <Spinner
                                        v-if="
                                            form.processing || isProcessingJob
                                        "
                                    />
                                </div>
                            </span>
                        </div>
                        <Button
                            type="submit"
                            :tabindex="4"
                            :disabled="form.processing || isProcessingJob"
                            data-test="login-button"
                        >
                            <Spinner v-if="form.processing" />
                            Process
                        </Button>
                    </div>

                    <!-- Progress Bar -->
                    <div v-if="progress > 0" class="w-full">
                        <div
                            class="h-2 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700"
                        >
                            <div
                                class="h-2 rounded-full bg-blue-600 transition-all duration-500 ease-out"
                                :style="{ width: progress + '%' }"
                            ></div>
                        </div>
                        <p class="mt-1 text-xs text-muted-foreground">
                            {{ statusText }} ({{ progress }}%)
                        </p>
                    </div>
                </div>
            </form>
        </Card>

        <!-- Blog Content -->
        <div v-if="blogsState.length > 0">
            <Card class="mt-4 p-4">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <Link href="#" v-for="blog in blogsState" :key="blog.id">
                        <Card
                            class="flex h-full w-full items-start justify-between gap-4 p-4"
                        >
                            <div class="flex-1">
                                <CardHeader class="pb-2">
                                    <CardTitle>{{ blog.title }}</CardTitle>
                                    <CardDescription>{{
                                        blog.description
                                    }}</CardDescription>
                                </CardHeader>
                                <CardContent class="pb-2">
                                    <p>
                                        {{
                                            $helpers.wordLimit(blog.content, 20)
                                        }}
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
            <Card class="mt-4 p-4">
                <CardContent>
                    <p class="text-muted-foreground">No blog posts found.</p>
                </CardContent>
            </Card>
        </div>
    </div>
</template>
