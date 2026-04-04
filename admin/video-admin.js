// ===================================
// VIDEO GALLERY ADMIN
// Manages YouTube videos via localStorage
// ===================================

const VIDEO_KEY = 'rnr_videos';
const CATEGORY_LABELS = {
    wedding: 'Wedding Films',
    events: 'Events',
    corporate: 'Corporate',
    commercial: 'Commercial'
};

function getVideos() {
    try { return JSON.parse(localStorage.getItem(VIDEO_KEY)) || []; } catch(e) { return []; }
}

function saveVideos(videos) {
    localStorage.setItem(VIDEO_KEY, JSON.stringify(videos));
}

function extractYouTubeId(input) {
    input = input.trim();
    // Shorts URL → auto vertical
    const shortsMatch = input.match(/youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/);
    if (shortsMatch) return { id: shortsMatch[1], autoVertical: true };
    // youtu.be/ID
    const shortMatch = input.match(/youtu\.be\/([a-zA-Z0-9_-]{11})/);
    if (shortMatch) return { id: shortMatch[1], autoVertical: false };
    // watch?v=ID
    const watchMatch = input.match(/[?&]v=([a-zA-Z0-9_-]{11})/);
    if (watchMatch) return { id: watchMatch[1], autoVertical: false };
    // embed/ID
    const embedMatch = input.match(/embed\/([a-zA-Z0-9_-]{11})/);
    if (embedMatch) return { id: embedMatch[1], autoVertical: false };
    // bare 11-char ID
    if (/^[a-zA-Z0-9_-]{11}$/.test(input)) return { id: input, autoVertical: false };
    return null;
}

function renderVideoAdmin() {
    const grid = document.getElementById('videoAdminGrid');
    const countEl = document.getElementById('videoCount');
    if (!grid) return;

    const videos = getVideos();
    if (countEl) countEl.textContent = `${videos.length} video${videos.length !== 1 ? 's' : ''}`;

    if (!videos.length) {
        grid.innerHTML = '<p style="color:#888;padding:2rem 0;">No videos added yet. Add your first YouTube video above.</p>';
        return;
    }

    grid.innerHTML = videos.map((v, i) => {
        const isVertical = v.vertical;
        const aspectPadding = isVertical ? '177.78%' : '56.25%';
        const maxWidth = isVertical ? '320px' : '100%';
        return `
        <div class="gallery-item-admin" style="background:#1e1e1e;border:1px solid #333;border-radius:12px;overflow:hidden;max-width:${maxWidth};">
            <div style="position:relative;padding-top:${aspectPadding};background:#000;">
                <iframe src="https://www.youtube.com/embed/${v.videoId}?rel=0&modestbranding=1"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                    style="position:absolute;top:0;left:0;width:100%;height:100%;">
                </iframe>
            </div>
            <div style="padding:1rem;">
                <h4 style="color:#fff;font-size:0.95rem;margin-bottom:0.3rem;">${v.title}</h4>
                <p style="color:#FF4D00;font-size:0.8rem;margin-bottom:0.5rem;">${CATEGORY_LABELS[v.category] || v.category} • ${v.year}${isVertical ? ' • Vertical' : ''}</p>
                ${v.description ? `<p style="color:#888;font-size:0.82rem;margin-bottom:0.8rem;">${v.description}</p>` : ''}
                <p style="color:#555;font-size:0.75rem;margin-bottom:0.8rem;">ID: ${v.videoId}</p>
                <button onclick="deleteVideo(${i})" style="background:#e53e3e;color:#fff;border:none;padding:0.4rem 1rem;border-radius:6px;cursor:pointer;font-size:0.82rem;">Delete</button>
            </div>
        </div>`;
    }).join('');
}

function deleteVideo(index) {
    if (!confirm('Delete this video?')) return;
    const videos = getVideos();
    videos.splice(index, 1);
    saveVideos(videos);
    renderVideoAdmin();
    showVideoMessage('Video deleted.', 'success');
}

function showVideoMessage(msg, type) {
    const el = document.getElementById('videoMessage');
    if (!el) return;
    el.textContent = msg;
    el.style.display = 'block';
    el.className = `message message-${type}`;
    setTimeout(() => { el.style.display = 'none'; }, 4000);
}

// Form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('videoForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const url = document.getElementById('youtubeUrl').value;
        const parsed = extractYouTubeId(url);
        if (!parsed) {
            showVideoMessage('Invalid YouTube URL. Paste a youtube.com or youtu.be link.', 'error');
            return;
        }

        const { id: videoId, autoVertical } = parsed;
        const manualVertical = document.getElementById('videoVertical').checked;
        const vertical = autoVertical || manualVertical;

        const category = document.getElementById('videoCategory').value;
        const title = document.getElementById('videoTitle').value.trim();
        const year = document.getElementById('videoYear').value || new Date().getFullYear();
        const description = document.getElementById('videoDesc').value.trim();

        const videos = getVideos();

        if (videos.some(v => v.videoId === videoId)) {
            showVideoMessage('This video is already in the gallery.', 'error');
            return;
        }

        videos.unshift({
            videoId,
            title,
            category,
            categoryLabel: CATEGORY_LABELS[category] || category,
            year: parseInt(year),
            description,
            vertical,
            addedAt: new Date().toISOString()
        });

        saveVideos(videos);
        form.reset();
        document.getElementById('videoYear').value = new Date().getFullYear();
        renderVideoAdmin();
        showVideoMessage(`"${title}" added successfully!`, 'success');
    });

    renderVideoAdmin();
});
