<div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner" id="banner-adv-items">
        <!-- JavaScriptで動的に挿入される -->
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<script>
    // 非同期で広告を取得し、表示する
    async function loadAdvertisements() {
        try {
            const advertisement = await get_advertisement(5, "banner");   
            if (advertisement && advertisement.length > 0) {
                const bannerAdvlInner = document.getElementById('banner-adv-items');
                bannerAdvlInner.innerHTML = ''; // 既存のスライドをクリア

                advertisement.forEach((ad, index) => {
                    const isActive = index === 0 ? 'active' : ''; // 最初のスライドをアクティブに
                    const item = `
                        <div class="carousel-item ${isActive}" data-bs-interval="5000">
                            <a href="${ad.href}" rel="nofollow" onclick="adv_click(${ad.id})">
                                <img src="${ad.src}" class="d-block w-100">
                                <img border="0" width="1" height="1" src="${ad.tracking_src}">
                            </a>
                        </div>
                    `;
                    bannerAdvlInner.insertAdjacentHTML('beforeend', item);
                });
            } else {
                console.log('adv_nothing');
            }
        } catch (error) {
            console.error('広告の取得中にエラーが発生しました:', error);
        }
    }
    // ページ読み込み時に広告を表示
    document.addEventListener('DOMContentLoaded', loadAdvertisements);
</script>

