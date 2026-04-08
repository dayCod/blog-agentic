export const helpers = {
    wordLimit(content: String, limit: number): string {
        return content.split(/\s+/).slice(0, limit).join(' ') + (content.length > limit ? '...' : '')
    }
}
