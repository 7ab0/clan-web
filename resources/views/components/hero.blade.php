<!-- Start Hero -->
    <Section>
        {{-- TODO(Clan): banner_top_all.png es el placeholder del template, compartido por todas las subpáginas; falta foto real --}}
        <div class="ak-commmon-hero ak-style1 ak-bg" data-src="{{ asset('assets/img/banner_top_all.png') }}">
            <div class="ak-commmon-heading">
                <div class="ak-section-heading ak-style-1 ak-type-1 ak-color-1 page-top-title">
                    <div class="ak-section-subtitle">
                        <a href="{{ route('index') }}">Home</a> / <?php echo (isset($title) ? $title   : '')?>
                    </div>
                    <h2 class="ak-section-title page-title-anim"><?php echo (isset($subTitle) ? $subTitle   : '')?></h2>
                </div>
            </div>
        </div>
    </Section>
<!-- End Hero -->