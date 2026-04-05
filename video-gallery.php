<?php
$pageTitle       = 'Video Gallery | Reel and Roll Photography';
$pageDescription = 'Watch our videography portfolio — wedding films, event coverage, corporate and commercial videos.';
$activeNav       = 'videography';
require_once 'includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="fade-in-up">Video <span class="text-gradient">Gallery</span></h1>
            <p class="page-subtitle fade-in-up">Cinematic storytelling capturing the essence of your moments</p>
            <div class="page-breadcrumb fade-in-up">
                <a href="/">Home</a>
                <span>/</span>
                <span>Video Gallery</span>
            </div>
        </div>
    </section>

    <!-- Video Gallery -->
    <section class="section" style="background: var(--color-dark);">
        <div class="container">
            <div class="gallery-filters reveal" id="videoFilters">
                <button class="filter-btn active" data-cat="all">All</button>
                <button class="filter-btn" data-cat="wedding">Wedding Films</button>
                <button class="filter-btn" data-cat="events">Events</button>
                <button class="filter-btn" data-cat="corporate">Corporate</button>
                <button class="filter-btn" data-cat="commercial">Commercial</button>
            </div>

            <div id="videoGrid" class="vimeo-grid" style="margin-top: 3rem;">
                <div class="empty-state" style="text-align:center; padding: 5rem 0; color: var(--color-gray);">
                    <p style="font-size:1.2rem;">Loading videos...</p>
                </div>
            </div>
        </div>
    </section>

<?php require_once 'includes/footer.php'; ?>

<script>
(function () {
    const STORAGE_KEY = 'rnr_videos';
    const grid        = document.getElementById('videoGrid');
    const filterBtns  = document.querySelectorAll('#videoFilters .filter-btn');

    function getVideos() {
        try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) || []; } catch (e) { return []; }
    }

    function renderVideos(cat) {
        const videos   = getVideos();
        const filtered = cat === 'all' ? videos : videos.filter(v => v.category === cat);

        if (!filtered.length) {
            grid.innerHTML = '<div class="empty-state" style="text-align:center;padding:5rem 0;color:var(--color-gray);"><p style="font-size:1.2rem;">No videos in this category yet.</p></div>';
            return;
        }

        grid.innerHTML = filtered.map(v => `
            <div class="vimeo-card${v.vertical ? ' vertical' : ''}" data-cat="${v.category}">
                <div class="vimeo-wrapper">
                    <iframe src="https://www.youtube.com/embed/${v.videoId}?rel=0&modestbranding=1&iv_load_policy=3&cc_load_policy=0&color=white"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen title="${v.title}">
                    </iframe>
                </div>
                <div class="vimeo-info">
                    <h4>${v.title}</h4>
                    <p class="vimeo-meta">${v.categoryLabel} • ${v.year}${v.vertical ? ' • Vertical' : ''}</p>
                    ${v.description ? `<p class="vimeo-desc">${v.description}</p>` : ''}
                </div>
            </div>
        `).join('');
    }

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            renderVideos(this.dataset.cat);
        });
    });

    renderVideos('all');
})();
</script>
