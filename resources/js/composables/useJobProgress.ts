import { ref, onUnmounted } from 'vue';

export function useJobProgress() {
    const progress = ref(0);
    const statusText = ref('');
    const isProcessingJob = ref(false);
    const currentJobId = ref<string | null>(null);

    const stopListening = () => {
        if (currentJobId.value) {
            window.Echo.leaveChannel(`job-progress.${currentJobId.value}`);
            currentJobId.value = null;
            isProcessingJob.value = false;
        }
    };

    const listenToJob = (newJobId: string, registerCustomListeners?: (channel: any) => void) => {
        stopListening();
        
        currentJobId.value = newJobId;
        isProcessingJob.value = true;
        statusText.value = 'Menunggu antrean...';
        progress.value = 0;

        const channel = window.Echo.channel(`job-progress.${newJobId}`);
        
        channel.listen('.JobUpdated', (e: { progress: number; status: string }) => {
            progress.value = e.progress;
            statusText.value = e.status;

            if (e.progress >= 100) {
                setTimeout(() => {
                    progress.value = 0;
                    statusText.value = '';
                    isProcessingJob.value = false;
                    window.Echo.leaveChannel(`job-progress.${newJobId}`);
                }, 2000);
            }
        });

        if (registerCustomListeners) {
            registerCustomListeners(channel);
        }
    };

    onUnmounted(() => {
        stopListening();
    });

    return {
        progress,
        statusText,
        isProcessingJob,
        currentJobId,
        listenToJob,
        stopListening
    };
}
