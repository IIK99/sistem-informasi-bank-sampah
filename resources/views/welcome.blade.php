<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah — Ekosistem Daur Ulang Digital</title>

    @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/js/app.js'])

    <style>
        :root {
            --go-black: #050c09;
            --go-dark: #071912;
            --go-forest: #0a2e20;
            --go-green: #00aa13;
            --go-lime: #b4f105;
            --go-bg: #f5f7f6;
            --go-surface: #ffffff;
            --text-main: #0f172a;
            --text-sub: #475569;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--go-bg);
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.6;
        }

        .container-custom {
            width: 100%;
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 2rem;
        }

      
        .go-navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1050;
            padding: 1.4rem 0;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            background: linear-gradient(180deg, rgba(5, 12, 9, 0.75) 0%, rgba(5, 12, 9, 0) 100%);
        }

        .go-navbar.scrolled {
            padding: 0.85rem 0;
            background: rgba(7, 25, 18, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.28);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand-wrap {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: #ffffff;
            font-weight: 800;
            font-size: 1.35rem;
            letter-spacing: -0.03em;
        }

        .brand-mark {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--go-lime);
            color: var(--go-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease;
        }

        .brand-wrap:hover .brand-mark {
            transform: scale(1.06);
        }

        .nav-menu-links {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .nav-item-link {
            color: rgba(255, 255, 255, 0.82);
            text-decoration: none;
            font-size: 0.92rem;
            font-weight: 600;
            padding: 0.5rem 1.15rem;
            border-radius: 999px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .nav-item-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
        }

        .nav-item-link.active {
            background: var(--go-lime);
            color: var(--go-dark) !important;
            font-weight: 700;
            box-shadow: 0 0 22px rgba(180, 241, 5, 0.45);
        }

        .btn-portal {
            background: #ffffff;
            color: var(--go-dark);
            font-weight: 700;
            font-size: 0.9rem;
            padding: 0.65rem 1.5rem;
            border-radius: 999px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-portal:hover {
            background: var(--go-lime);
            color: var(--go-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(180, 241, 5, 0.4);
        }

        /* =========================================================
           2. FULLSCREEN VIDEO HERO ALA GOJEK.IO
           ========================================================= */
        .hero-video-wrapper {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 8rem 0 6rem;
            overflow: hidden;
            background: var(--go-black);
        }

        .hero-bg-video {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            transform: translate(-50%, -50%);
            object-fit: cover;
            z-index: 1;
            opacity: 0.78;
            filter: contrast(1.08) saturate(1.1);
        }

        /* Vignette sinematik ala gojek.io agar teks tajam namun video tetap jelas */
        .hero-video-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(90deg, rgba(5, 12, 9, 0.88) 0%, rgba(5, 12, 9, 0.52) 55%, rgba(5, 12, 9, 0.45) 100%),
                linear-gradient(180deg, rgba(5, 12, 9, 0.35) 0%, rgba(5, 12, 9, 0.15) 50%, rgba(5, 12, 9, 0.85) 100%);
            z-index: 2;
        }

        .hero-content-layer {
            position: relative;
            z-index: 3;
            width: 100%;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 3.5rem;
            align-items: center;
        }

        .hero-heading {
            font-size: clamp(2.6rem, 5.2vw, 4.4rem);
            font-weight: 800;
            color: #ffffff;
            line-height: 1.08;
            letter-spacing: -0.035em;
            margin-bottom: 1.5rem;
        }

        .hero-heading span {
            display: block;
        }

        .hero-heading .text-lime {
            color: var(--go-lime);
        }

        .hero-desc {
            color: rgba(255, 255, 255, 0.82);
            font-size: 1.12rem;
            max-width: 520px;
            margin-bottom: 2.5rem;
            font-weight: 400;
            line-height: 1.65;
        }

        .hero-cta-group {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .btn-lime-solid {
            background: var(--go-lime);
            color: var(--go-dark);
            font-weight: 800;
            font-size: 0.98rem;
            padding: 0.95rem 2rem;
            border-radius: 999px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            transition: all 0.3s ease;
        }

        .btn-lime-solid:hover {
            background: #c5ff29;
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(180, 241, 5, 0.45);
            color: var(--go-dark);
        }

        .btn-outline-glass {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.28);
            padding: 0.95rem 2rem;
            border-radius: 999px;
            font-weight: 700;
            font-size: 0.98rem;
            text-decoration: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(8px);
        }

        .btn-outline-glass:hover {
            background: rgba(255, 255, 255, 0.18);
            color: #ffffff;
            transform: translateY(-2px);
        }
        .waste-3d-stage {
            perspective: 1200px;
            width: 100%;
            height: 440px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .waste-3d-scene {
            width: 100%;
            max-width: 440px;
            height: 400px;
            position: relative;
            transform-style: preserve-3d;
        }

        .waste-glass-card {
            position: absolute;
            background: rgba(12, 34, 25, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 20px;
            padding: 1.1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            width: 225px;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.45);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .waste-glass-card:hover {
            border-color: var(--go-lime);
            box-shadow: 0 28px 56px rgba(0, 0, 0, 0.6);
        }

        .waste-icon-box {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: rgba(180, 241, 5, 0.12);
            color: var(--go-lime);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .waste-meta h6 {
            font-size: 0.92rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0 0 0.2rem 0;
        }

        .waste-rate {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--go-lime);
        }

        .pos-1 {
            top: 10px;
            left: 0;
            transform: translateZ(70px);
            animation: floatA 5.5s ease-in-out infinite;
        }

        .pos-2 {
            top: 35px;
            right: -10px;
            transform: translateZ(40px);
            animation: floatB 6.2s ease-in-out infinite;
        }

        .pos-3 {
            bottom: 85px;
            left: -10px;
            transform: translateZ(90px);
            animation: floatC 5.8s ease-in-out infinite;
        }

        .pos-4 {
            bottom: 60px;
            right: 0;
            transform: translateZ(55px);
            animation: floatD 6.5s ease-in-out infinite;
        }

        .waste-summary-bar {
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%) translateZ(115px);
            background: rgba(7, 25, 18, 0.92);
            border: 1px solid rgba(180, 241, 5, 0.45);
            color: #ffffff;
            padding: 1rem 1.4rem;
            border-radius: 18px;
            width: 310px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            backdrop-filter: blur(16px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.55);
        }

        @keyframes floatA {

            0%,
            100% {
                transform: translateZ(70px) translateY(0);
            }

            50% {
                transform: translateZ(70px) translateY(-10px);
            }
        }

        @keyframes floatB {

            0%,
            100% {
                transform: translateZ(40px) translateY(0);
            }

            50% {
                transform: translateZ(40px) translateY(10px);
            }
        }

        @keyframes floatC {

            0%,
            100% {
                transform: translateZ(90px) translateY(0);
            }

            50% {
                transform: translateZ(90px) translateY(-11px);
            }
        }

        @keyframes floatD {

            0%,
            100% {
                transform: translateZ(55px) translateY(0);
            }

            50% {
                transform: translateZ(55px) translateY(9px);
            }
        }

        [data-reveal] {
            opacity: 0;
            transition: opacity 0.85s cubic-bezier(0.16, 1, 0.3, 1),
                transform 0.85s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        [data-reveal="fade-up"] {
            transform: translateY(44px);
        }

        [data-reveal="fade-right"] {
            transform: translateX(-48px);
        }

        [data-reveal="zoom-in"] {
            transform: scale(0.92);
        }

        [data-reveal].revealed {
            opacity: 1;
            transform: translate(0, 0) scale(1);
        }
        
        .section-block {
            padding: 6.5rem 0;
            scroll-margin-top: 84px;
        }

        .section-main-title {
            font-size: clamp(2rem, 3.6vw, 2.9rem);
            font-weight: 800;
            color: var(--go-dark);
            letter-spacing: -0.03em;
            line-height: 1.18;
        }

        .about-panel {
            background: #ffffff;
            border-radius: 28px;
            padding: 3.25rem;
            box-shadow: 0 18px 45px rgba(7, 25, 18, 0.05);
            border: 1px solid rgba(7, 25, 18, 0.06);
            margin-bottom: 3rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 22px;
            padding: 2rem 1.75rem;
            border: 1px solid rgba(7, 25, 18, 0.07);
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s ease;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(7, 25, 18, 0.09);
            border-color: var(--go-forest);
        }

        .stat-number {
            font-size: clamp(2.1rem, 3.2vw, 2.75rem);
            font-weight: 800;
            color: var(--go-forest);
            line-height: 1.1;
            margin-bottom: 0.45rem;
            font-variant-numeric: tabular-nums;
        }

        /* FITUR GRID */
        .section-fitur-dark {
            background: linear-gradient(180deg, var(--go-dark) 0%, #05130e 100%);
            color: #ffffff;
        }

        .section-fitur-dark .section-main-title {
            color: #ffffff;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-top: 3rem;
        }

        .feature-box {
            background: rgba(16, 51, 37, 0.75);
            border: 1px solid rgba(180, 241, 5, 0.16);
            border-radius: 24px;
            padding: 2.4rem 2rem;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .feature-box:hover {
            transform: translateY(-10px);
            background: rgba(20, 64, 46, 0.95);
            border-color: var(--go-lime);
            box-shadow: 0 22px 44px rgba(0, 0, 0, 0.45);
        }

        .feature-box h4 {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--go-lime);
            margin-bottom: 0.85rem;
            letter-spacing: -0.02em;
            line-height: 1.3;
        }

        .feature-box p {
            font-size: 0.94rem;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.65;
            margin: 0;
        }

        /* =========================================================
           GOJEK.IO STYLE ANIMATED CAROUSEL SLIDER (CARA KERJA)
           ========================================================= */
        .gojek-slider-section {
            position: relative;
            padding: 6.5rem 0 5.5rem;
            width: 100%;
            overflow: hidden;
            scroll-margin-top: 84px;
        }

        .gojek-section-header {
            text-align: center;
            max-width: 780px;
            margin: 0 auto 2.25rem;
        }

        .gojek-eyebrow {
            display: inline-block;
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--go-green);
            background: rgba(0, 170, 19, 0.1);
            padding: 0.4rem 1.1rem;
            border-radius: 999px;
            margin-bottom: 0.85rem;
        }

        .gojek-section-subtitle {
            font-size: 1.05rem;
            color: var(--text-sub);
            margin-top: 0.75rem;
            line-height: 1.6;
        }

        /* Top Step Navigation Pills (Category Tabs ala Gojek) */
        .gojek-tabs-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 0.65rem;
            margin-bottom: 2.25rem;
        }

        .gojek-tab-btn {
            background: #ffffff;
            color: var(--text-sub);
            border: 1px solid rgba(7, 25, 18, 0.1);
            font-size: 0.88rem;
            font-weight: 600;
            padding: 0.6rem 1.35rem;
            border-radius: 999px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .gojek-tab-btn:hover {
            color: var(--go-dark);
            border-color: var(--go-forest);
            transform: translateY(-2px);
        }

        .gojek-tab-btn.active {
            background: var(--go-dark);
            color: #ffffff;
            border-color: var(--go-dark);
            box-shadow: 0 8px 24px rgba(7, 25, 18, 0.22);
            font-weight: 700;
        }

        .gojek-tab-btn.active .tab-step-num {
            background: var(--go-lime);
            color: var(--go-dark);
        }

        .tab-step-num {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: rgba(7, 25, 18, 0.08);
            font-size: 0.72rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        /* Full Viewport Slider Outer with Floating Arrows */
        .gojek-viewport-outer {
            position: relative;
            width: 100%;
            overflow: hidden;
            padding: 1.5rem 0 2rem;
        }

        .gojek-viewport {
            position: relative;
            width: 100%;
            overflow: visible;
            user-select: none;
            -webkit-user-select: none;
            cursor: grab;
        }

        .gojek-viewport:active {
            cursor: grabbing;
        }

        .gojek-track {
            display: flex;
            gap: 1.75rem;
            transition: transform 0.65s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform;
            align-items: stretch;
        }

        /* Large Gojek Product Slide Card with Peek Effect */
        .gojek-slide-card {
            flex: 0 0 clamp(320px, 82vw, 1080px);
            border-radius: 40px;
            padding: 3.5rem 3.5rem 3.25rem;
            color: #ffffff;
            position: relative;
            overflow: hidden;
            transition: transform 0.65s cubic-bezier(0.16, 1, 0.3, 1),
                opacity 0.65s ease,
                filter 0.65s ease,
                box-shadow 0.65s ease;
            transform: scale(0.93);
            opacity: 0.55;
            filter: blur(1px);
            cursor: pointer;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
        }

        .gojek-slide-card.active {
            transform: scale(1);
            opacity: 1;
            filter: blur(0);
            cursor: default;
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.28);
        }

        /* Distinctive Vibrant Background Colors */
        .gojek-theme-green {
            background: linear-gradient(135deg, #00aa13 0%, #00880f 100%);
        }

        .gojek-theme-blue {
            background: linear-gradient(135deg, #0077e6 0%, #0055b3 100%);
        }

        /* Magenta Pink exactly like user's Gojek.io screenshot */
        .gojek-theme-magenta {
            background: linear-gradient(135deg, #e4007f 0%, #ba0065 100%);
        }

        .gojek-theme-purple {
            background: linear-gradient(135deg, #7928ca 0%, #581c87 100%);
        }

        /* Subtle Geometric Watermark Decor */
        .gojek-bg-decor {
            position: absolute;
            right: -60px;
            bottom: -70px;
            width: 360px;
            height: 360px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.16) 0%, rgba(255, 255, 255, 0) 70%);
            pointer-events: none;
        }

        /* Card Header */
        .gojek-card-header {
            margin-bottom: 2.75rem;
            position: relative;
            z-index: 2;
        }

        .gojek-step-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(8px);
            padding: 0.35rem 1rem;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 0.85rem;
            border: 1px solid rgba(255, 255, 255, 0.28);
        }

        .gojek-card-title {
            font-size: clamp(2rem, 3.8vw, 3rem);
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.035em;
            line-height: 1.12;
            margin: 0;
        }

        /* 4 Columns Grid Inside Card (Exact Gojek.io Pattern) */
        .gojek-items-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            position: relative;
            z-index: 2;
        }

        .gojek-item-box {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
            transition: transform 0.3s ease;
        }

        .gojek-slide-card.active .gojek-item-box:hover {
            transform: translateY(-4px);
        }

        .gojek-item-head {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .gojek-item-icon {
            width: 36px;
            height: 36px;
            border-radius: 11px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .gojek-slide-card.active .gojek-item-box:hover .gojek-item-icon {
            background: #ffffff;
            color: var(--go-dark);
            transform: scale(1.08);
        }

        .gojek-item-name {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            color: #ffffff;
            line-height: 1.2;
            text-transform: lowercase;
        }

        .gojek-item-desc {
            font-size: 0.94rem;
            color: rgba(255, 255, 255, 0.88);
            line-height: 1.55;
            margin: 0;
            font-weight: 400;
        }

        /* Staggered Item Entrance Animation when Slide Becomes Active */
        @keyframes gojekItemIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .gojek-slide-card.active .gojek-item-box:nth-child(1) {
            animation: gojekItemIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.08s backwards;
        }

        .gojek-slide-card.active .gojek-item-box:nth-child(2) {
            animation: gojekItemIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.16s backwards;
        }

        .gojek-slide-card.active .gojek-item-box:nth-child(3) {
            animation: gojekItemIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.24s backwards;
        }

        .gojek-slide-card.active .gojek-item-box:nth-child(4) {
            animation: gojekItemIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.32s backwards;
        }

        /* Floating Arrow Chevrons (< and >) ala Gojek.io */
        .gojek-arrow-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.28);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1.5px solid rgba(255, 255, 255, 0.55);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 25;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        }

        .gojek-arrow-btn:hover {
            background: #ffffff;
            color: var(--go-dark);
            transform: translateY(-50%) scale(1.12);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.35);
        }

        .gojek-arrow-btn:active {
            transform: translateY(-50%) scale(0.95);
        }

        .gojek-prev-btn {
            left: clamp(0.75rem, 3.5vw, 3rem);
        }

        .gojek-next-btn {
            right: clamp(0.75rem, 3.5vw, 3rem);
        }

        /* Bottom Indicators / Progress Dots */
        .gojek-indicators-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.65rem;
            margin-top: 1.75rem;
        }

        .gojek-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: rgba(7, 25, 18, 0.2);
            border: none;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            padding: 0;
        }

        .gojek-dot:hover {
            background: rgba(7, 25, 18, 0.45);
        }

        .gojek-dot.active {
            width: 40px;
            background: var(--go-forest);
            box-shadow: 0 0 14px rgba(10, 46, 32, 0.35);
        }

        /* Responsive Breakpoints for Gojek Slider */
        @media (max-width: 1100px) {
            .gojek-slide-card {
                padding: 2.75rem 2.5rem;
            }

            .gojek-items-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.75rem 2rem;
            }
        }

        @media (max-width: 768px) {
            .gojek-slide-card {
                flex: 0 0 88vw;
                border-radius: 28px;
                padding: 2.25rem 1.65rem;
            }

            .gojek-card-header {
                margin-bottom: 1.85rem;
            }

            .gojek-card-title {
                font-size: 1.85rem;
            }

            .gojek-items-grid {
                grid-template-columns: 1fr;
                gap: 1.35rem;
            }

            .gojek-arrow-btn {
                width: 44px;
                height: 44px;
            }

            .gojek-prev-btn {
                left: 0.35rem;
            }

            .gojek-next-btn {
                right: 0.35rem;
            }
        }

        /* KONTAK & FOOTER */
        .contact-panel {
            background: linear-gradient(135deg, var(--go-forest) 0%, var(--go-dark) 100%);
            border-radius: 30px;
            padding: 3.5rem;
            color: #ffffff;
            box-shadow: 0 25px 60px rgba(7, 25, 18, 0.2);
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 3rem;
            align-items: center;
        }

        .contact-info-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 2rem;
        }

        .contact-info-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: rgba(255, 255, 255, 0.06);
            padding: 1.1rem 1.35rem;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .contact-svg-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: rgba(180, 241, 5, 0.14);
            color: var(--go-lime);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .startup-footer {
            background: var(--go-black);
            color: #94a3b8;
            padding: 4.5rem 0 2.5rem;
            margin-top: 4rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1.3fr;
            gap: 2.5rem;
            padding-bottom: 3rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .footer-col-title {
            color: #ffffff;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 1.2rem;
        }

        .footer-link-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.7rem;
        }

        .footer-link-list a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.92rem;
            transition: color 0.2s ease;
        }

        .footer-link-list a:hover {
            color: var(--go-lime);
        }

        @media (max-width: 991px) {

            .hero-grid,
            .contact-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid,
            .features-grid,
            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .nav-menu-links {
                display: none;
            }

            .waste-3d-stage {
                height: 420px;
            }
        }

        @media (max-width: 576px) {

            .stats-grid,
            .features-grid,
            .footer-grid {
                grid-template-columns: 1fr;
            }

            .contact-panel,
            .about-panel {
                padding: 2rem 1.5rem;
            }

            .waste-glass-card {
                width: 175px;
                padding: 0.85rem;
            }
        }
    </style>
</head>

<body>

    <!-- ================= NAVBAR STICKY ALA GOJEK.IO ================= -->
    <nav class="go-navbar" id="mainNavbar">
        <div class="container-custom nav-inner">
            <a href="#beranda" class="brand-wrap">
                <img src="{{ asset('Screenshot 2026-10-23 103504-Photoroom.png') }}" alt="Logo"
                    style="height: 32px; width: auto; object-fit: contain;">
                <span>Bank Sampah</span>
            </a>

            <div class="nav-menu-links" id="scrollspyMenu">
                <a href="#tentang" class="nav-item-link">Tentang</a>
                <a href="#fitur" class="nav-item-link">Fitur</a>
                <a href="#cara-kerja" class="nav-item-link">Cara Kerja</a>
                <a href="#kontak" class="nav-item-link">Kontak</a>
            </div>

            <div>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-portal">Dashboard Saya</a>
                @else
                    <a href="{{ route('login') }}" class="btn-portal">Masuk Portal</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- ================= FULL-BLEED VIDEO HERO ALA GOJEK.IO ================= -->
    <header class="hero-video-wrapper" id="beranda">
        <!-- Direct High-Speed MP4 Video Sources (Bisa di-klik kanan & download untuk disimpan lokal) -->
        <video class="hero-bg-video" id="heroVideo" autoplay loop muted playsinline preload="auto">
            <source src="{{ asset('background.mp4') }}" type="video/mp4">.
        </video>

        <div class="hero-video-overlay"></div>

        <div class="container-custom hero-content-layer">
            <div class="hero-grid">
                <!-- Kiri: Tipografi Tegas ala Gojek.io -->
                <div data-reveal="fade-right">
                    <h1 class="hero-heading">
                        <span>Pilah Sampahnya.</span>
                        <span>Timbang di Pos.</span>
                        <span class="text-lime">Kumpulkan Uangnya.</span>
                    </h1>
                    <p class="hero-desc">
                        Platform pengelolaan sampah anorganik dan buku tabungan warga. Ubah plastik,
                        kardus, kaleng, dan logam menjadi saldo tunai yang transparan.
                    </p>
                    <div class="hero-cta-group">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-lime-solid">Buka Buku Tabungan</a>
                        @else
                            <a href="{{ route('login') }}" class="btn-lime-solid">Mulai Menabung</a>
                        @endauth
                        <a href="#cara-kerja" class="btn-outline-glass">Lihat Cara Kerja</a>
                    </div>
                </div>

                <!-- Kanan: 3D Interactive Glass Showcase (Tanpa Badge Berlebihan) -->
                <div class="waste-3d-stage" id="wasteStage" data-reveal="zoom-in">
                    <div class="waste-3d-scene" id="wasteScene">
                        <!-- 1. Botol Plastik PET -->
                        <div class="waste-glass-card pos-1">
                            <div class="waste-icon-box">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M9 2h6v3H9z" />
                                    <path d="M8 5h8l2 4v11a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V9l2-4z" />
                                    <line x1="6" y1="12" x2="18" y2="12" />
                                </svg>
                            </div>
                            <div class="waste-meta">
                                <h6>Botol Plastik PET</h6>
                                <div class="waste-rate">Rp 4.500 / kg</div>
                            </div>
                        </div>

                        <!-- 2. Kardus & Kertas -->
                        <div class="waste-glass-card pos-2">
                            <div class="waste-icon-box">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                                    <line x1="12" y1="22.08" x2="12" y2="12" />
                                </svg>
                            </div>
                            <div class="waste-meta">
                                <h6>Kardus & Kertas</h6>
                                <div class="waste-rate">Rp 3.200 / kg</div>
                            </div>
                        </div>

                        <!-- 3. Kaleng Minuman -->
                        <div class="waste-glass-card pos-3">
                            <div class="waste-icon-box">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <ellipse cx="12" cy="5" rx="7" ry="3" />
                                    <path d="M5 5v14c0 1.66 3.13 3 7 3s7-1.34 7-3V5" />
                                    <path d="M5 12c0 1.66 3.13 3 7 3s7-1.34 7-3" />
                                </svg>
                            </div>
                            <div class="waste-meta">
                                <h6>Kaleng Aluminium</h6>
                                <div class="waste-rate">Rp 14.000 / kg</div>
                            </div>
                        </div>

                        <!-- 4. Besi & Logam -->
                        <div class="waste-glass-card pos-4">
                            <div class="waste-icon-box">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polygon points="12 2 2 7 12 12 22 7 12 2" />
                                    <polyline points="2 17 12 22 22 17" />
                                    <polyline points="2 12 12 17 22 12" />
                                </svg>
                            </div>
                            <div class="waste-meta">
                                <h6>Besi & Logam</h6>
                                <div class="waste-rate">Rp 7.500 / kg</div>
                            </div>
                        </div>

                        <!-- Bar Ringkasan Nilai Setoran -->
                        <div class="waste-summary-bar">
                            <div>
                                <div
                                    style="font-size: 0.72rem; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 0.05em;">
                                    Simulasi Nilai Setoran</div>
                                <div style="font-size: 1.25rem; font-weight: 800; color: var(--go-lime);">+ Rp 78.150
                                </div>
                            </div>
                            <div style="font-size: 0.82rem; font-weight: 700; color: #ffffff;">
                                13,3 Kg Terpilah
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- ================= 1. SECTION TENTANG & COUNTER ANGKA ================= -->
    <section id="tentang" class="section-block">
        <div class="container-custom">
            <div class="about-panel" data-reveal="fade-up">
                <h2 class="section-main-title" style="margin-bottom: 1rem; max-width: 820px;">
                    Sampah rumah tangga yang terpilah bukan lagi limbah, melainkan tabungan nyata bagi keluarga.
                </h2>
                <p style="color: var(--text-sub); max-width: 760px; font-size: 1.05rem;">
                    Kami menggantikan pencatatan buku manual yang rawan terselip dengan sistem kasir dan buku tabungan
                    digital terpadu. Setiap penimbangan botol plastik, kardus, hingga logam dihitung otomatis dan
                    langsung masuk ke rekening warga detik itu juga.
                </p>
            </div>

            <div class="stats-grid">
                <div class="stat-card" data-reveal="fade-up" style="transition-delay: 0.05s;">
                    <div class="stat-number">
                        <span class="counter-value" data-target="101" data-decimals="0">0</span>+
                    </div>
                    <div style="font-weight: 700; color: var(--go-dark);">Nasabah Warga Aktif</div>
                    <p style="font-size: 0.85rem; color: var(--text-sub); margin-top: 0.3rem;">Keluarga terdaftar yang
                        rutin menyetorkan sampah terpilah.</p>
                </div>

                <div class="stat-card" data-reveal="fade-up" style="transition-delay: 0.15s;">
                    <div class="stat-number">
                        <span class="counter-value" data-target="12.8" data-decimals="1">0</span> Ton
                    </div>
                    <div style="font-weight: 700; color: var(--go-dark);">Sampah Terdaur Ulang</div>
                    <p style="font-size: 0.85rem; color: var(--text-sub); margin-top: 0.3rem;">Total volume sampah
                        anorganik yang dikelola kembali.</p>
                </div>

                <div class="stat-card" data-reveal="fade-up" style="transition-delay: 0.25s;">
                    <div class="stat-number">
                        Rp <span class="counter-value" data-target="18.5" data-decimals="1">0</span> Jt
                    </div>
                    <div style="font-weight: 700; color: var(--go-dark);">Nilai Kas Tersalurkan</div>
                    <p style="font-size: 0.85rem; color: var(--text-sub); margin-top: 0.3rem;">Akumulasi dana tabungan
                        warga yang telah dicairkan.</p>
                </div>

                <div class="stat-card" data-reveal="fade-up" style="transition-delay: 0.35s;">
                    <div class="stat-number">
                        <span class="counter-value" data-target="100" data-decimals="0">0</span>%
                    </div>
                    <div style="font-weight: 700; color: var(--go-dark);">Transparansi Mutasi</div>
                    <p style="font-size: 0.85rem; color: var(--text-sub); margin-top: 0.3rem;">Seluruh riwayat
                        timbangan dan penarikan tercatat digital.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= 2. SECTION FITUR (CLEAN SVG, TANPA EMOTICON) ================= -->
    <section id="fitur" class="section-block section-fitur-dark">
        <div class="container-custom">
            <div style="max-width: 640px;" data-reveal="fade-up">
                <h2 class="section-main-title">Dirancang Praktis untuk Warga dan Petugas Pos</h2>
            </div>

            <div class="features-grid">
                <div class="feature-box" data-reveal="fade-up" style="transition-delay: 0.05s;">
                    <h4>Buku Tabungan Digital</h4>
                    <p>Pantau saldo aktif, rincian berat per jenis sampah, dan mutasi kas langsung dari ponsel.</p>
                </div>

                <div class="feature-box" data-reveal="fade-up" style="transition-delay: 0.15s;">
                    <h4>Kasir Multi-Item Kilat</h4>
                    <p>Petugas menimbang banyak kategori sampah sekaligus dalam satu nota dengan kalkulasi otomatis.</p>
                </div>

                <div class="feature-box" data-reveal="fade-up" style="transition-delay: 0.25s;">
                    <h4>Katalog Harga Terbuka</h4>
                    <p>Daftar harga beli plastik, kardus, aluminium, dan besi selalu diperbarui mengikuti harga
                        pengepul.</p>
                </div>

                <div class="feature-box" data-reveal="fade-up" style="transition-delay: 0.35s;">
                    <h4>Notifikasi Siaran Email</h4>
                    <p>Pengumuman jadwal buka pos penimbangan dikirim otomatis ke alamat email masing-masing warga.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= 3. SECTION CARA KERJA (FULL ANIMASI ALA GOJEK.IO) ================= -->
    <section id="cara-kerja" class="section-block gojek-slider-section">
        <div class="container-custom">
            <!-- Header Section -->
            <div class="gojek-section-header" data-reveal="fade-up">
                <span class="gojek-eyebrow">Alur & Cara Kerja</span>
                <h2 class="section-main-title">Empat Langkah Mudah Menabung Sampah</h2>
                <p class="gojek-section-subtitle">
                    Dari pemilahan mandiri di rumah hingga saldo tunai cair ke tangan Anda, seluruh proses tercatat
                    digital, transparan, dan bebas repot.
                </p>
            </div>

            <!-- Top Step Navigation Pills (Category Tabs ala Gojek) -->
            <div class="gojek-tabs-bar" data-reveal="fade-up" style="transition-delay: 0.08s;">
                <button type="button" class="gojek-tab-btn active" data-slide-target="0">
                    <span class="tab-step-num">1</span>
                    <span>Pilah dari Rumah</span>
                </button>
                <button type="button" class="gojek-tab-btn" data-slide-target="1">
                    <span class="tab-step-num">2</span>
                    <span>Bawa ke Pos RW</span>
                </button>
                <button type="button" class="gojek-tab-btn" data-slide-target="2">
                    <span class="tab-step-num">3</span>
                    <span>Timbang & Catat</span>
                </button>
                <button type="button" class="gojek-tab-btn" data-slide-target="3">
                    <span class="tab-step-num">4</span>
                    <span>Tarik Saldo Tunai</span>
                </button>
            </div>
        </div>

        <!-- Full-Width Peeking Slider Viewport -->
        <div class="gojek-viewport-outer" data-reveal="zoom-in" style="transition-delay: 0.15s;">
            <!-- Left Chevron Arrow Button -->
            <button type="button" class="gojek-arrow-btn gojek-prev-btn" id="gojekPrevBtn"
                aria-label="Langkah Sebelumnya">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>

            <!-- Right Chevron Arrow Button -->
            <button type="button" class="gojek-arrow-btn gojek-next-btn" id="gojekNextBtn"
                aria-label="Langkah Selanjutnya">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>

            <!-- Slider Viewport -->
            <div class="gojek-viewport" id="gojekViewport">
                <div class="gojek-track" id="gojekTrack">

                    <!-- SLIDE 1: PILAH DARI RUMAH (Emerald Green Gojek Theme) -->
                    <div class="gojek-slide-card gojek-theme-green active" data-slide-index="0">
                        <div class="gojek-bg-decor"></div>
                        <div class="gojek-card-header">
                            <h3 class="gojek-card-title">Pilah Sampah dari Rumah</h3>
                        </div>

                        <div class="gojek-items-grid">
                            <!-- Item 1 -->
                            <div class="gojek-item-box">
                                <div class="gojek-item-head">
                                    <div class="gojek-item-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M9 2h6v3H9z" />
                                            <path d="M8 5h8l2 4v11a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V9l2-4z" />
                                        </svg>
                                    </div>
                                    <div class="gojek-item-name">botol plastik</div>
                                </div>
                                <p class="gojek-item-desc">Cuci bersih & keringkan botol PET bening, tutup botol, serta
                                    wadah plastik HDPE.</p>
                            </div>

                            <!-- Item 2 -->
                            <div class="gojek-item-box">
                                <div class="gojek-item-head">
                                    <div class="gojek-item-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path
                                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                            <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                                            <line x1="12" y1="22.08" x2="12" y2="12" />
                                        </svg>
                                    </div>
                                    <div class="gojek-item-name">kardus kertas</div>
                                </div>
                                <p class="gojek-item-desc">Lipat kemasan karton box cokelat, tumpuk koran, dan ikat
                                    kertas agar rapi.</p>
                            </div>

                            <!-- Item 3 -->
                            <div class="gojek-item-box">
                                <div class="gojek-item-head">
                                    <div class="gojek-item-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <ellipse cx="12" cy="5" rx="7" ry="3" />
                                            <path d="M5 5v14c0 1.66 3.13 3 7 3s7-1.34 7-3V5" />
                                        </svg>
                                    </div>
                                    <div class="gojek-item-name">kaleng soda</div>
                                </div>
                                <p class="gojek-item-desc">Kumpulkan kaleng minuman aluminium & wadah biskuit dalam
                                    keadaan kering.</p>
                            </div>

                            <!-- Item 4 -->
                            <div class="gojek-item-box">
                                <div class="gojek-item-head">
                                    <div class="gojek-item-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polygon points="12 2 2 7 12 12 22 7 12 2" />
                                            <polyline points="2 17 12 22 22 17" />
                                            <polyline points="2 12 12 17 22 12" />
                                        </svg>
                                    </div>
                                    <div class="gojek-item-name">besi logam</div>
                                </div>
                                <p class="gojek-item-desc">Kumpulkan paku, kawat, peralatan dapur afkir, hingga tembaga
                                    bernilai tinggi.</p>
                            </div>
                        </div>
                    </div>

                    <!-- SLIDE 2: BAWA KE POS RW (Electric Blue Theme) -->
                    <div class="gojek-slide-card gojek-theme-blue" data-slide-index="1">
                        <div class="gojek-bg-decor"></div>
                        <div class="gojek-card-header">
                            <h3 class="gojek-card-title">Bawa ke Pos Lingkungan</h3>
                        </div>

                        <div class="gojek-items-grid">
                            <!-- Item 1 -->
                            <div class="gojek-item-box">
                                <div class="gojek-item-head">
                                    <div class="gojek-item-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10" />
                                            <polyline points="12 6 12 12 16 14" />
                                        </svg>
                                    </div>
                                    <div class="gojek-item-name">jadwal setiap minggu</div>
                                </div>
                                <p class="gojek-item-desc">Pos operasional penimbangan buka rutin setiap Minggu pagi
                                    pukul 08.00 – 12.00 WIB.</p>
                            </div>

                            <!-- Item 2 -->
                            <div class="gojek-item-box">
                                <div class="gojek-item-head">
                                    <div class="gojek-item-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M3 21h18" />
                                            <path d="M5 21V7l8-4v18" />
                                            <path d="M19 21V11l-6-3" />
                                        </svg>
                                    </div>
                                    <div class="gojek-item-name">pos RW 012</div>
                                </div>
                                <p class="gojek-item-desc">Berlokasi di sekretariat warga RW 012 Curug Kulon, Kabupaten
                                    Tangerang.</p>
                            </div>

                            <!-- Item 3 -->
                            <div class="gojek-item-box">
                                <div class="gojek-item-head">
                                    <div class="gojek-item-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <rect width="14" height="20" x="5" y="2" rx="2"
                                                ry="2" />
                                            <path d="M12 18h.01" />
                                        </svg>
                                    </div>
                                    <div class="gojek-item-name">buku digital</div>
                                </div>
                                <p class="gojek-item-desc">Cukup sebutkan nama warga atau username akun tabungan yang
                                    telah terdaftar.</p>
                            </div>

                            <!-- Item 4 -->
                            <div class="gojek-item-box">
                                <div class="gojek-item-head">
                                    <div class="gojek-item-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                            <circle cx="9" cy="7" r="4" />
                                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                        </svg>
                                    </div>
                                    <div class="gojek-item-name">petugas ramah</div>
                                </div>
                                <p class="gojek-item-desc">Disambut dan didampingi langsung oleh pengurus RT/RW yang
                                    bertugas melayani.</p>
                            </div>
                        </div>
                    </div>

                    <!-- SLIDE 3: TIMBANG & CATAT (MAGENTA PINK PERSIS GAMBAR GOJEK DARI USER!) -->
                    <div class="gojek-slide-card gojek-theme-magenta" data-slide-index="2">
                        <div class="gojek-bg-decor"></div>
                        <div class="gojek-card-header">
                            <h3 class="gojek-card-title">Timbang & Catat Otomatis</h3>
                        </div>

                        <div class="gojek-items-grid">
                            <!-- Item 1 -->
                            <div class="gojek-item-box">
                                <div class="gojek-item-head">
                                    <div class="gojek-item-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10" />
                                            <path d="m16 12-4-4-4 4" />
                                            <path d="M12 16V8" />
                                        </svg>
                                    </div>
                                    <div class="gojek-item-name">skala presisi</div>
                                </div>
                                <p class="gojek-item-desc">Penimbangan terbuka disaksikan langsung dengan ketelitian
                                    timbangan 0,01 Kg.</p>
                            </div>

                            <!-- Item 2 -->
                            <div class="gojek-item-box">
                                <div class="gojek-item-head">
                                    <div class="gojek-item-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
                                        </svg>
                                    </div>
                                    <div class="gojek-item-name">kasir kilat</div>
                                </div>
                                <p class="gojek-item-desc">Petugas menginput banyak jenis sampah sekaligus dalam satu
                                    lembar nota setoran.</p>
                            </div>

                            <!-- Item 3 -->
                            <div class="gojek-item-box">
                                <div class="gojek-item-head">
                                    <div class="gojek-item-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M12 2v20" />
                                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                                        </svg>
                                    </div>
                                    <div class="gojek-item-name">harga terbuka</div>
                                </div>
                                <p class="gojek-item-desc">Kalkulasi nilai rupiah otomatis mengikuti katalog harga
                                    terkini tanpa potongan liar.</p>
                            </div>

                            <!-- Item 4 -->
                            <div class="gojek-item-box">
                                <div class="gojek-item-head">
                                    <div class="gojek-item-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                            <polyline points="14 2 14 8 20 8" />
                                            <line x1="16" y1="13" x2="8" y2="13" />
                                            <line x1="16" y1="17" x2="8" y2="17" />
                                        </svg>
                                    </div>
                                    <div class="gojek-item-name">nota digital</div>
                                </div>
                                <p class="gojek-item-desc">Nota transaksi terbit seketika, tercatat aman di server dan
                                    siap dipantau nasabah.</p>
                            </div>
                        </div>
                    </div>

                    <!-- SLIDE 4: TARIK SALDO TUNAI (Royal Purple Theme) -->
                    <div class="gojek-slide-card gojek-theme-purple" data-slide-index="3">
                        <div class="gojek-bg-decor"></div>
                        <div class="gojek-card-header">
                            <h3 class="gojek-card-title">Tarik Saldo Tunai Kapan Saja</h3>
                        </div>

                        <div class="gojek-items-grid">
                            <!-- Item 1 -->
                            <div class="gojek-item-box">
                                <div class="gojek-item-head">
                                    <div class="gojek-item-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10" />
                                            <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" />
                                            <path d="M12 18V6" />
                                        </svg>
                                    </div>
                                    <div class="gojek-item-name">saldo instan</div>
                                </div>
                                <p class="gojek-item-desc">Nominal rupiah otomatis terakumulasi ke buku tabungan
                                    digital dalam hitungan detik.</p>
                            </div>

                            <!-- Item 2 -->
                            <div class="gojek-item-box">
                                <div class="gojek-item-head">
                                    <div class="gojek-item-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <rect width="20" height="14" x="2" y="3" rx="2" />
                                            <line x1="8" y1="21" x2="16" y2="21" />
                                            <line x1="12" y1="17" x2="12" y2="21" />
                                        </svg>
                                    </div>
                                    <div class="gojek-item-name">pantau gadget</div>
                                </div>
                                <p class="gojek-item-desc">Buka smartphone untuk memantau saldo aktif, rincian setoran,
                                    dan grafik berkala.</p>
                            </div>

                            <!-- Item 3 -->
                            <div class="gojek-item-box">
                                <div class="gojek-item-head">
                                    <div class="gojek-item-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4" />
                                            <path d="M3 5v14a2 2 0 0 0 2 2h16v-5" />
                                            <path d="M18 12a2 2 0 0 0 0 4h4v-4Z" />
                                        </svg>
                                    </div>
                                    <div class="gojek-item-name">tarik mudah</div>
                                </div>
                                <p class="gojek-item-desc">Ajukan penarikan tunai kapan pun dibutuhkan tanpa dipungut
                                    biaya administrasi.</p>
                            </div>

                            <!-- Item 4 -->
                            <div class="gojek-item-box">
                                <div class="gojek-item-head">
                                    <div class="gojek-item-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                            <polyline points="7 10 12 15 17 10" />
                                            <line x1="12" y1="15" x2="12" y2="3" />
                                        </svg>
                                    </div>
                                    <div class="gojek-item-name">unduh pdf</div>
                                </div>
                                <p class="gojek-item-desc">Kwitansi resmi pencairan berformat PDF dapat diunduh
                                    langsung sebagai arsip keluarga.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-custom">
            <!-- Bottom Indicators / Progress Dots -->
            <div class="gojek-indicators-bar">
                <button type="button" class="gojek-dot active" data-slide-target="0" aria-label="Slide 1"></button>
                <button type="button" class="gojek-dot" data-slide-target="1" aria-label="Slide 2"></button>
                <button type="button" class="gojek-dot" data-slide-target="2" aria-label="Slide 3"></button>
                <button type="button" class="gojek-dot" data-slide-target="3" aria-label="Slide 4"></button>
            </div>
        </div>
    </section>

    <!-- ================= 4. SECTION KONTAK ================= -->
    <section id="kontak" class="section-block" style="padding-bottom: 2rem;">
        <div class="container-custom">
            <div class="contact-panel" data-reveal="zoom-in">
                <div class="contact-grid">
                    <div>
                        <h2 style="font-size: clamp(1.85rem, 3vw, 2.5rem); font-weight: 800; margin-bottom: 0.75rem;">
                            Pos Operasional Bank Sampah
                        </h2>
                        <p style="color: rgba(255,255,255,0.75); font-size: 1rem;">
                            Kunjungi sekretariat pos penimbangan kami atau masuk ke portal warga untuk memantau tabungan
                            Anda.
                        </p>

                        <div class="contact-info-list">
                            <div class="contact-info-item">
                                <div class="contact-svg-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                        <circle cx="12" cy="10" r="3" />
                                    </svg>
                                </div>
                                <div>
                                    <div style="font-weight: 700;">Pos Bank Sampah RW 012</div>
                                    <div style="font-size: 0.85rem; color: rgba(255,255,255,0.7);">Desa Curug Kulon,
                                        Kabupaten Tangerang</div>
                                </div>
                            </div>

                            <div class="contact-info-item">
                                <div class="contact-svg-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                </div>
                                <div>
                                    <div style="font-weight: 700;">Jam Operasional Penimbangan</div>
                                    <div style="font-size: 0.85rem; color: rgba(255,255,255,0.7);">Setiap Minggu, Pukul
                                        08.00 – 12.00 WIB</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12); padding: 2.5rem; border-radius: 24px;">
                        <h4 style="font-weight: 800; margin-bottom: 0.6rem;">Akses Portal Nasabah & Petugas</h4>
                        <p style="font-size: 0.92rem; color: rgba(255,255,255,0.72); margin-bottom: 1.75rem;">
                            Gunakan akun warga yang telah terdaftar untuk mengecek saldo dan mengajukan penarikan dana.
                        </p>
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-lime-solid"
                                style="width: 100%; justify-content: center;">
                                Masuk ke Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-lime-solid"
                                style="width: 100%; justify-content: center;">
                                Login ke Portal Sistem
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= FOOTER ================= -->
    <footer class="startup-footer">
        <div class="container-custom">
            <div class="footer-grid">
                <div>
                    <a href="#beranda" class="brand-wrap" style="margin-bottom: 1rem;">
                        <div class="brand-mark">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.4" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M7 19H4.815a1.83 1.83 0 0 1-1.57-.881 1.785 1.785 0 0 1-.004-1.784L7.196 9.5" />
                                <path
                                    d="M11 19h8.203a1.83 1.83 0 0 0 1.556-.89 1.784 1.784 0 0 0 0-1.775l-1.226-2.12" />
                                <path d="m14 16-3 3 3 3" />
                                <path d="M8.293 13.596 7.196 9.5 3.1 10.598" />
                                <path
                                    d="m9.344 5.811 1.093-1.892A1.83 1.83 0 0 1 11.985 3a1.784 1.784 0 0 1 1.546.888l3.943 6.843" />
                                <path d="m13.378 9.633 4.096 1.098 1.097-4.096" />
                            </svg>
                        </div>
                        <span>Bank Sampah</span>
                    </a>
                    <p style="font-size: 0.9rem; max-width: 300px;">
                        Platform pengelolaan sampah anorganik dan buku tabungan digital warga untuk lingkungan yang
                        bersih dan mandiri.
                    </p>
                </div>

                <div>
                    <div class="footer-col-title">Navigasi</div>
                    <ul class="footer-link-list">
                        <li><a href="#beranda">Beranda</a></li>
                        <li><a href="#tentang">Tentang Kami</a></li>
                        <li><a href="#fitur">Fitur Sistem</a></li>
                        <li><a href="#cara-kerja">Cara Kerja</a></li>
                    </ul>
                </div>

                <div>
                    <div class="footer-col-title">Layanan Warga</div>
                    <ul class="footer-link-list">
                        <li><a href="{{ route('login') }}">Cek Saldo Tabungan</a></li>
                        <li><a href="{{ route('login') }}">Riwayat Timbangan</a></li>
                        <li><a href="{{ route('login') }}">Pencairan Tunai</a></li>
                        <li><a href="{{ route('login') }}">Kasir Petugas</a></li>
                    </ul>
                </div>

                <div>
                    <div class="footer-col-title">Katalog Penerimaan</div>
                    <ul class="footer-link-list">
                        <li><a href="#cara-kerja">Botol Plastik PET & HDPE</a></li>
                        <li><a href="#cara-kerja">Kardus, Koran & Kertas</a></li>
                        <li><a href="#cara-kerja">Kaleng Aluminium</a></li>
                        <li><a href="#cara-kerja">Besi, Tembaga & Logam</a></li>
                    </ul>
                </div>
            </div>

            <div
                style="padding-top: 2rem; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; font-size: 0.85rem; gap: 1rem;">
                <div>&copy; {{ date('Y') }} Bank Sampah RW 012 Desa Curug Kulon.</div>
                <div>Lingkungan Bersih, Tabungan Terisi.</div>
            </div>
        </div>
    </footer>

    <!-- ================= ENGINE ANIMASI JAVASCRIPT ================= -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Pastikan video langsung berputar mulus
            const heroVideo = document.getElementById('heroVideo');
            if (heroVideo) {
                heroVideo.play().catch(() => {});
            }

            // 1. STICKY NAVBAR TRANSITION
            const navbar = document.getElementById('mainNavbar');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 40) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            }, {
                passive: true
            });

            // 2. SCROLLSPY: MENU OTOMATIS MENYALA SESUAI SECTION
            const sections = document.querySelectorAll('section[id], header[id]');
            const navLinks = document.querySelectorAll('.nav-item-link');

            const spyObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const activeId = entry.target.getAttribute('id');
                        navLinks.forEach(link => {
                            link.classList.toggle('active', link.getAttribute('href') ===
                                `#${activeId}`);
                        });
                    }
                });
            }, {
                rootMargin: '-30% 0px -55% 0px'
            });

            sections.forEach(sec => spyObserver.observe(sec));

            // 3. SCROLL REVEAL ANIMATION
            const revealElements = document.querySelectorAll('[data-reveal]');
            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                    } else if (entry.boundingClientRect.top > window.innerHeight * 0.9) {
                        entry.target.classList.remove('revealed');
                    }
                });
            }, {
                threshold: 0.12
            });

            revealElements.forEach(el => revealObserver.observe(el));

            // 4. ANIMASI ANGKA NAIK DARI 0 KE TARGET
            const counters = document.querySelectorAll('.counter-value');
            const animateCounter = (el) => {
                const target = parseFloat(el.getAttribute('data-target'));
                const decimals = parseInt(el.getAttribute('data-decimals') || '0', 10);
                const duration = 2000;
                const startTime = performance.now();

                const step = (now) => {
                    const progress = Math.min((now - startTime) / duration, 1);
                    const ease = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
                    const current = target * ease;

                    el.textContent = current.toFixed(decimals).replace('.', ',');

                    if (progress < 1) {
                        requestAnimationFrame(step);
                    } else {
                        el.textContent = target.toFixed(decimals).replace('.', ',');
                    }
                };
                requestAnimationFrame(step);
            };

            const counterObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounter(entry.target);
                    } else {
                        entry.target.textContent = '0';
                    }
                });
            }, {
                threshold: 0.5
            });

            counters.forEach(c => counterObserver.observe(c));

            // 5. INTERAKSI 3D KUMPULAN SAMPAH DENGAN SMOOTH LERP
            const stage = document.getElementById('wasteStage');
            const scene = document.getElementById('wasteScene');
            let targetX = 0,
                targetY = 0,
                currentX = 0,
                currentY = 0;

            if (stage && scene) {
                stage.addEventListener('mousemove', (e) => {
                    const rect = stage.getBoundingClientRect();
                    const x = (e.clientX - rect.left) / rect.width - 0.5;
                    const y = (e.clientY - rect.top) / rect.height - 0.5;
                    targetY = x * 24;
                    targetX = -y * 24;
                });

                stage.addEventListener('mouseleave', () => {
                    targetX = 0;
                    targetY = 0;
                });

                const update3D = () => {
                    currentX += (targetX - currentX) * 0.08;
                    currentY += (targetY - currentY) * 0.08;
                    scene.style.transform =
                        `rotateX(${currentX.toFixed(2)}deg) rotateY(${currentY.toFixed(2)}deg)`;
                    requestAnimationFrame(update3D);
                };
                requestAnimationFrame(update3D);
            }

            // 6. GOJEK.IO STYLE FULL-ANIMATED SLIDER ENGINE (CARA KERJA)
            const gojekTrack = document.getElementById('gojekTrack');
            const gojekViewport = document.getElementById('gojekViewport');
            const gojekSlides = document.querySelectorAll('.gojek-slide-card');
            const gojekPrevBtn = document.getElementById('gojekPrevBtn');
            const gojekNextBtn = document.getElementById('gojekNextBtn');
            const gojekTabs = document.querySelectorAll('.gojek-tab-btn');
            const gojekDots = document.querySelectorAll('.gojek-dot');

            if (gojekTrack && gojekViewport && gojekSlides.length > 0) {
                let currentSlide = 0;
                let autoplayTimer = null;
                const totalSlides = gojekSlides.length;

                // Function to update carousel position with center-peeking alignment
                function updateSlider(animate = true) {
                    if (animate) {
                        gojekTrack.style.transition = 'transform 0.65s cubic-bezier(0.16, 1, 0.3, 1)';
                    } else {
                        gojekTrack.style.transition = 'none';
                    }

                    const viewportWidth = gojekViewport.clientWidth;
                    const activeSlideEl = gojekSlides[currentSlide];
                    if (!activeSlideEl) return;
                    const slideWidth = activeSlideEl.offsetWidth;

                    // Read actual CSS gap from track
                    const trackStyles = window.getComputedStyle(gojekTrack);
                    const gap = parseFloat(trackStyles.gap) || parseFloat(trackStyles.columnGap) || 28;

                    // Calculate translation so active slide is dead center
                    const centerOffset = (viewportWidth - slideWidth) / 2;
                    const translateX = centerOffset - (currentSlide * (slideWidth + gap));

                    gojekTrack.style.transform = `translateX(${translateX}px)`;

                    // Update active classes on slides
                    gojekSlides.forEach((slide, idx) => {
                        const isActive = idx === currentSlide;
                        slide.classList.toggle('active', isActive);
                        slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
                    });

                    // Update tabs
                    gojekTabs.forEach((tab, idx) => {
                        tab.classList.toggle('active', idx === currentSlide);
                    });

                    // Update dots
                    gojekDots.forEach((dot, idx) => {
                        dot.classList.toggle('active', idx === currentSlide);
                    });
                }

                function goToSlide(index) {
                    currentSlide = (index + totalSlides) % totalSlides;
                    updateSlider(true);
                    restartAutoplay();
                }

                function nextSlide() {
                    goToSlide(currentSlide + 1);
                }

                function prevSlide() {
                    goToSlide(currentSlide - 1);
                }

                // Autoplay handlers
                function startAutoplay() {
                    stopAutoplay();
                    autoplayTimer = setInterval(nextSlide, 5000);
                }

                function stopAutoplay() {
                    if (autoplayTimer) {
                        clearInterval(autoplayTimer);
                        autoplayTimer = null;
                    }
                }

                function restartAutoplay() {
                    stopAutoplay();
                    startAutoplay();
                }

                // Event Listeners for Controls
                if (gojekPrevBtn) {
                    gojekPrevBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        prevSlide();
                    });
                }

                if (gojekNextBtn) {
                    gojekNextBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        nextSlide();
                    });
                }

                // Click on peeking slide to activate it
                gojekSlides.forEach((slide, idx) => {
                    slide.addEventListener('click', () => {
                        if (idx !== currentSlide) {
                            goToSlide(idx);
                        }
                    });
                });

                // Tab buttons
                gojekTabs.forEach((tab) => {
                    tab.addEventListener('click', () => {
                        const targetIndex = parseInt(tab.getAttribute('data-slide-target') || '0',
                            10);
                        goToSlide(targetIndex);
                    });
                });

                // Dot buttons
                gojekDots.forEach((dot) => {
                    dot.addEventListener('click', () => {
                        const targetIndex = parseInt(dot.getAttribute('data-slide-target') || '0',
                            10);
                        goToSlide(targetIndex);
                    });
                });

                // Pause on hover
                const sliderSection = document.getElementById('cara-kerja');
                if (sliderSection) {
                    sliderSection.addEventListener('mouseenter', stopAutoplay);
                    sliderSection.addEventListener('mouseleave', startAutoplay);
                }

                // Touch & Drag Swipe support
                let isDragging = false;
                let startPos = 0;
                let dragDistance = 0;

                function getPositionX(event) {
                    return event.type.includes('mouse') ? event.pageX : event.touches[0].clientX;
                }

                function dragStart(event) {
                    isDragging = true;
                    startPos = getPositionX(event);
                    dragDistance = 0;
                    stopAutoplay();
                }

                function dragMove(event) {
                    if (!isDragging) return;
                    const currentPos = getPositionX(event);
                    dragDistance = currentPos - startPos;
                }

                function dragEnd() {
                    if (!isDragging) return;
                    isDragging = false;
                    if (dragDistance < -50) {
                        nextSlide();
                    } else if (dragDistance > 50) {
                        prevSlide();
                    } else {
                        updateSlider(true);
                        startAutoplay();
                    }
                    dragDistance = 0;
                }

                gojekViewport.addEventListener('touchstart', dragStart, {
                    passive: true
                });
                gojekViewport.addEventListener('touchmove', dragMove, {
                    passive: true
                });
                gojekViewport.addEventListener('touchend', dragEnd);

                gojekViewport.addEventListener('mousedown', dragStart);
                window.addEventListener('mousemove', dragMove);
                window.addEventListener('mouseup', dragEnd);

                // Keyboard arrow navigation when cara-kerja is in viewport
                window.addEventListener('keydown', (e) => {
                    const sectionRect = document.getElementById('cara-kerja')?.getBoundingClientRect();
                    if (sectionRect && sectionRect.top < window.innerHeight && sectionRect.bottom > 0) {
                        if (e.key === 'ArrowLeft') {
                            prevSlide();
                        } else if (e.key === 'ArrowRight') {
                            nextSlide();
                        }
                    }
                });

                // Window Resize handler with debounce
                let resizeTimeout;
                window.addEventListener('resize', () => {
                    clearTimeout(resizeTimeout);
                    resizeTimeout = setTimeout(() => {
                        updateSlider(false);
                    }, 80);
                });

                // Initial render
                setTimeout(() => {
                    updateSlider(false);
                    startAutoplay();
                }, 60);
            }
        });
    </script>
</body>

</html>
