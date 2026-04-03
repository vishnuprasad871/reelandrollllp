// ===================================
// VIDEO GALLERY ADMIN
// Manages Vimeo videos via localStorage
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

function extractVimeoId(input) {
    input = input.trim();
    // Already an ID (all digits)
    if (/^\d+$/.test(input)) return input;
    // URL: vimeo.com/123456789 or player.vimeo.com/video/123456789
    const match = input.match(/(?:vimeo\.com\/(?:video\/)?)(\d+)/);
    return match ? match[1] : null;
}

function renderVideoAdmin() {
    const grid = document.getElementById('videoAdminGrid');
    const countEl = document.getElementById('videoCount');
    if (!grid) return;

    const videos = getVideos();
    if (countEl) countEl.textContent = `${videos.length} video${videos.length !== 1 ? 's' : ''}`;

    if (!videos.length) {
        grid.innerHTML = '<p style="color:#888;padding:2rem 0;">No videos added yet. Add your first Vimeo video above.</p>';
        return;
    }

    grid.innerHTML = videos.map((v, i) => `
        <div class="gallery-item-admin" style="background:#1e1e1e;border:1px solid #333;border-radius:12px;overflow:hidden;">
            <div style="position:relative;padding-top:56.25%;background:#000;">
                <iframe src="https://player.vimeo.com/video/${v.vimeoId}?badge=0"
                    frameborder="0" allow="fullscreen"
                    style="position:absolute;top:0;left:0;width:100%;height:100%;">
                </iframe>
            </div>
            <div style="padding:1rem;">
                <h4 style="color:#fff;font-size:0.95rem;margin-bottom:0.3rem;">${v.title}</h4>
                <p style="color:#FF4D00;font-size:0.8rem;margin-bottom:0.8rem;">${CATEGORY_LABELS[v.category] || v.category} • ${v.year}</p>
                ${v.description ? `<p style="color:#888;font-size:0.82rem;margin-bottom:0.8rem;">${v.description}</p>` : ''}
                <p style="color:#555;font-size:0.75rem;margin-bottom:0.8rem;">ID: ${v.vimeoId}</p>
                <button onclick="deleteVideo(${i})" style="background:#e53e3e;color:#fff;border:none;padding:0.4rem 1rem;border-radius:6px;cursor:pointer;font-size:0.82rem;">Delete</button>
            </div>
        </div>
    `).join('');
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
        const url = document.getElementById('vimeoUrl').value;
        const vimeoId = extractVimeoId(url);
        if (!vimeoId) {
            showVideoMessage('Invalid Vimeo URL or ID. Please enter a valid Vimeo link.', 'error');
            return;
        }

        const category = document.getElementById('videoCategory').value;
        const title = document.getElementById('videoTitle').value.trim();
        const year = document.getElementById('videoYear').value || new Date().getFullYear();
        const description = document.getElementById('videoDesc').value.trim();

        const videos = getVideos();

        // Check duplicate
        if (videos.some(v => v.vimeoId === vimeoId)) {
            showVideoMessage('This Vimeo video is already in the gallery.', 'error');
            return;
        }

        videos.unshift({
            vimeoId,
            title,
            category,
            categoryLabel: CATEGORY_LABELS[category] || category,
            year: parseInt(year),
            description,
            addedAt: new Date().toISOString()
        });

        saveVideos(videos);
        form.reset();
        document.getElementById('videoYear').value = '2024';
        renderVideoAdmin();
        showVideoMessage(`Video "${title}" added successfully!`, 'success');
    });

    renderVideoAdmin();
});
