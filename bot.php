<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta property="og:image" content="https://i.ibb.co/M8S0Zzj/live-streaming.png" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>$heading</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.plyr.io/3.6.12/plyr.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    :root {
      --bg-primary: #0f1117; --bg-secondary: #1a1d29; --bg-tertiary: #252936; --bg-glass: rgba(37, 41, 54, 0.8);
      --text-primary: #ffffff; --text-secondary: #a8b3cf; --text-muted: #6b7395; --accent-primary: #6366f1;
      --accent-secondary: #8b5cf6; --accent-gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
      --success: #10b981; --warning: #f59e0b; --danger: #ef4444; --border: rgba(255, 255, 255, 0.06);
      --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.2); --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.3); --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.4);
      --radius-sm: 8px; --radius-md: 12px; --radius-lg: 16px; --radius-xl: 24px;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; background: var(--bg-primary); color: var(--text-primary); min-height: 100vh; overflow-x: hidden; }
    .bg-decoration { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; opacity: 0.4; pointer-events: none; 
      background: radial-gradient(circle at 20% 30%, rgba(99, 102, 241, 0.08) 0%, transparent 50%), radial-gradient(circle at 80% 70%, rgba(139, 92, 246, 0.06) 0%, transparent 50%); }
    .app-wrapper { position: relative; z-index: 1; min-height: 100vh; display: flex; flex-direction: column; width: 100%; overflow-x: hidden; }
    header { background: rgba(26, 29, 41, 0.95); backdrop-filter: blur(10px); padding: 16px 4%; display: flex; align-items: center; justify-content: space-between; 
      position: sticky; top: 0; z-index: 1000; border-bottom: 1px solid var(--border); box-shadow: var(--shadow-md); width: 100%; gap: 12px; }
    .logo { display: flex; align-items: center; gap: 12px; font-family: 'Space Grotesk', sans-serif; font-size: 24px; font-weight: 700; 
      background: var(--accent-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; white-space: nowrap; }
    .logo-icon { width: 42px; height: 42px; background: var(--accent-gradient); border-radius: 12px; display: flex; align-items: center; justify-content: center; 
      font-size: 20px; color: white; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3); flex-shrink: 0; }
    .live-badge { background: var(--danger); color: white; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; 
      letter-spacing: 0.5px; display: flex; align-items: center; gap: 6px; white-space: nowrap; flex-shrink: 0; animation: pulse 2s infinite; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
    .live-badge::before { content: ''; width: 6px; height: 6px; background: white; border-radius: 50%; animation: blink 1s infinite; }
    @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0; } }
    #file-name { flex: 1; margin: 0 12px; overflow: hidden; min-width: 0; }
    #file-name marquee { color: var(--text-secondary); font-weight: 500; font-size: 15px; }
    .theme-switcher { display: flex; background: var(--bg-tertiary); border-radius: 50px; padding: 4px; gap: 4px; border: 1px solid var(--border); }
    .theme-btn { width: 36px; height: 36px; border: none; background: transparent; border-radius: 50%; cursor: pointer; font-size: 16px; 
      transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; color: var(--text-muted); flex-shrink: 0; }
    .theme-btn.active { background: var(--accent-gradient); color: white; box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3); }
    .theme-btn:hover:not(.active) { color: var(--text-primary); background: rgba(255, 255, 255, 0.05); }
    .main-container { flex: 1; padding: 30px 4%; max-width: 1600px; margin: 0 auto; width: 100%; display: grid; grid-template-columns: 1fr 360px; gap: 24px; align-items: start; }
    .player-wrapper { width: 100%; min-width: 0; }
    .player-container { background: var(--bg-secondary); border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--shadow-lg); 
      border: 1px solid var(--border); position: relative; width: 100%; transition: box-shadow 0.3s ease; }
    .player-container:hover { box-shadow: 0 8px 40px rgba(0, 0, 0, 0.5); }
    .player { width: 100%; aspect-ratio: 16/9; background: #000; display: block; }
    .video-info { padding: 24px; background: linear-gradient(to bottom, var(--bg-secondary), transparent); }
    .video-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; margin-bottom: 20px; }
    .video-title { font-family: 'Space Grotesk', sans-serif; font-size: 24px; font-weight: 700; color: var(--text-primary); line-height: 1.3; flex: 1; word-wrap: break-word; }
    .bookmark-btn { width: 44px; height: 44px; border: 1px solid var(--border); background: var(--bg-tertiary); border-radius: 50%; cursor: pointer; 
      font-size: 18px; color: var(--text-secondary); transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; flex-shrink: 0; position: relative; overflow: hidden; }
    .bookmark-btn.bookmarked { background: var(--success); color: white; border-color: var(--success); animation: bookmarkAdded 0.5s ease; }
    @keyframes bookmarkAdded { 0% { transform: scale(1); } 50% { transform: scale(1.2) rotate(10deg); } 100% { transform: scale(1); } }
    .bookmark-btn.bookmarked i { animation: checkmark 0.5s ease; }
    @keyframes checkmark { 0% { transform: scale(0) rotate(-180deg); } 50% { transform: scale(1.2) rotate(10deg); } 100% { transform: scale(1) rotate(0deg); } }
    .bookmark-btn:hover:not(.bookmarked) { background: var(--accent-gradient); color: white; border-color: transparent; transform: scale(1.05); }
    .video-stats { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
    .stat-item { display: flex; align-items: center; gap: 6px; padding: 8px 14px; background: var(--bg-tertiary); border-radius: var(--radius-md); 
      border: 1px solid var(--border); font-size: 13px; color: var(--text-secondary); font-weight: 500; transition: all 0.2s ease; white-space: nowrap; }
    .stat-item:hover { border-color: var(--accent-primary); background: rgba(99, 102, 241, 0.1); }
    .stat-item i { color: var(--accent-primary); font-size: 14px; }
    .stat-item.quality { background: rgba(99, 102, 241, 0.15); border-color: var(--accent-primary); color: var(--accent-primary); font-weight: 700; }
    .action-buttons { display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 10px; margin-bottom: 24px; }
    .action-btn { padding: 12px 16px; border: 1px solid var(--border); background: var(--bg-tertiary); border-radius: var(--radius-md); cursor: pointer; 
      font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s ease; 
      font-family: 'Inter', sans-serif; color: var(--text-primary); white-space: nowrap; }
    .action-btn:hover { border-color: var(--accent-primary); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2); }
    .action-btn i { font-size: 14px; }
    .btn-primary { background: var(--accent-gradient); border-color: transparent; color: white; }
    .btn-primary:hover { box-shadow: 0 4px 16px rgba(99, 102, 241, 0.4); }
    .share-section { margin-top: 24px; padding: 20px; background: var(--bg-glass); backdrop-filter: blur(10px); border-radius: var(--radius-lg); border: 1px solid var(--border); }
    .share-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
    .share-btn { padding: 12px 8px; border: none; border-radius: var(--radius-md); cursor: pointer; font-size: 11px; font-weight: 600; 
      display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px; transition: all 0.2s ease; font-family: 'Inter', sans-serif; text-decoration: none; }
    .share-btn i { font-size: 20px; transition: transform 0.2s ease; }
    .share-btn:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3); }
    .share-btn:hover i { transform: scale(1.1); }
    .btn-copy { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; }
    .btn-whatsapp { background: #25D366; color: white; }
    .btn-telegram { background: #0088cc; color: white; }
    .btn-facebook { background: #1877f2; color: white; }
    .btn-twitter { background: #1DA1F2; color: white; }
    .btn-instagram { background: linear-gradient(135deg, #833ab4, #fd1d1d, #fcb045); color: white; }
    .btn-messenger { background: #0084ff; color: white; }
    .btn-email { background: #ea4335; color: white; }
    .sidebar { position: sticky; top: 100px; width: 100%; max-width: 360px; }
    .sidebar-card { background: var(--bg-glass); backdrop-filter: blur(10px); border-radius: var(--radius-lg); padding: 20px; border: 1px solid var(--border); margin-bottom: 20px; box-shadow: var(--shadow-md); width: 100%; }
    .sidebar-title { font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 16px; font-family: 'Space Grotesk', sans-serif; display: flex; align-items: center; gap: 8px; }
    .sidebar-title i { color: var(--accent-primary); }
    .stats-grid { display: grid; gap: 12px; }
    .stat-card { background: var(--bg-tertiary); padding: 14px; border-radius: var(--radius-md); border: 1px solid var(--border); transition: all 0.2s ease; }
    .stat-card:hover { border-color: var(--accent-primary); transform: translateX(2px); }
    .stat-label { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; font-weight: 600; }
    .stat-value { font-size: 18px; font-weight: 700; color: var(--text-primary); font-family: 'Space Grotesk', sans-serif; }
    .quality-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: var(--accent-gradient); color: white; 
      border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3); }
    footer { background: linear-gradient(to bottom, var(--bg-secondary), rgba(26, 29, 41, 0.98)); padding: 60px 4% 30px; border-top: 1px solid var(--border); margin-top: 60px; width: 100%; position: relative; overflow: hidden; }
    footer::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px; background: var(--accent-gradient); opacity: 0.5; }
    .footer-content { max-width: 1600px; margin: 0 auto; width: 100%; position: relative; z-index: 1; }
    .footer-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 40px; margin-bottom: 50px; }
    .footer-logo { font-family: 'Space Grotesk', sans-serif; font-size: 26px; font-weight: 700; background: var(--accent-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .footer-description { color: var(--text-secondary); line-height: 1.7; font-size: 14px; }
    .footer-cta { display: inline-flex; align-items: center; gap: 10px; padding: 14px 24px; background: var(--accent-gradient); color: white; text-decoration: none; 
      border-radius: 50px; font-weight: 600; font-size: 14px; transition: all 0.2s ease; box-shadow: 0 4px 16px rgba(99, 102, 241, 0.3); align-self: flex-start; white-space: nowrap; }
    .footer-cta:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4); }
    .social-links { display: flex; gap: 10px; margin-top: 20px; flex-wrap: wrap; }
    .social-link { width: 40px; height: 40px; background: var(--bg-tertiary); border: 1px solid var(--border); border-radius: 50%; display: flex; align-items: center; 
      justify-content: center; color: var(--text-secondary); text-decoration: none; font-size: 16px; transition: all 0.2s ease; flex-shrink: 0; }
    .social-link:hover { background: var(--accent-gradient); color: white; border-color: transparent; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3); }
    .footer-section-title { font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 16px; font-family: 'Space Grotesk', sans-serif; }
    .footer-bottom { padding-top: 30px; border-top: 1px solid var(--border); text-align: center; position: relative; }
    .copyright-container { display: flex; flex-direction: column; align-items: center; gap: 20px; }
    .footer-copyright { font-size: 14px; color: var(--text-secondary); display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; 
      background: var(--bg-glass); backdrop-filter: blur(10px); border: 1px solid var(--border); border-radius: 50px; position: relative; overflow: hidden; transition: all 0.3s ease; }
    .footer-copyright::before { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: var(--accent-gradient); opacity: 0.1; transition: left 0.5s ease; }
    .footer-copyright:hover::before { left: 0; }
    .footer-copyright i { color: #ef4444; font-size: 12px; animation: heartbeat 1.5s ease-in-out infinite; }
    @keyframes heartbeat { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.2); } }
    .footer-copyright a { color: var(--accent-primary); text-decoration: none; font-weight: 600; transition: all 0.2s ease; position: relative; }
    .footer-copyright a::after { content: ''; position: absolute; bottom: -2px; left: 0; width: 0; height: 2px; background: var(--accent-gradient); transition: width 0.3s ease; }
    .footer-copyright a:hover::after { width: 100%; }
    .footer-bottom-links { display: flex; gap: 24px; align-items: center; justify-content: center; flex-wrap: wrap; }
    .footer-bottom-links a { color: var(--text-secondary); text-decoration: none; font-size: 13px; transition: all 0.2s ease; white-space: nowrap; padding: 8px 16px; border-radius: 20px; background: transparent; }
    .footer-bottom-links a:hover { color: var(--accent-primary); background: rgba(99, 102, 241, 0.1); }
    .dev-credit { margin-top: 16px; display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; 
      background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1)); border: 1px solid var(--accent-primary); 
      border-radius: 30px; font-size: 11px; color: var(--text-muted); letter-spacing: 0.5px; text-transform: uppercase; font-weight: 600; }
    .dev-credit i { color: var(--accent-primary); font-size: 12px; }
    .toast { position: fixed; bottom: 24px; right: 24px; background: var(--bg-glass); backdrop-filter: blur(20px); color: var(--text-primary); 
      padding: 16px 20px; border-radius: var(--radius-md); font-weight: 600; display: flex; align-items: center; gap: 12px; 
      box-shadow: var(--shadow-lg); border: 1px solid var(--success); transform: translateY(150px); opacity: 0; transition: all 0.3s ease; z-index: 10000; min-width: 260px; max-width: 400px; }
    .toast.show { transform: translateY(0); opacity: 1; }
    .toast.warning { border-color: var(--warning); }
    .toast.warning i { background: var(--warning); }
    .toast.error { border-color: var(--danger); }
    .toast.error i { background: var(--danger); }
    .toast i { width: 32px; height: 32px; background: var(--success); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
    .toast-content { flex: 1; min-width: 0; }
    .toast-title { font-size: 14px; font-weight: 700; margin-bottom: 2px; }
    .toast-message { font-size: 12px; color: var(--text-secondary); font-weight: 400; }
    body.dark { --bg-primary: #000000; --bg-secondary: #0a0a0a; --bg-tertiary: #151515; --border: rgba(255, 255, 255, 0.05); }
    body.light { --bg-primary: #f8f9fa; --bg-secondary: #ffffff; --bg-tertiary: #f1f3f5; --text-primary: #1a1a1a; --text-secondary: #4a4a4a; --text-muted: #6b6b6b; --border: rgba(0, 0, 0, 0.1); }
    .loading-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: var(--bg-primary); display: flex; align-items: center; justify-content: center; z-index: 9999; transition: opacity 0.5s ease, visibility 0.5s ease; }
    .loading-overlay.hidden { opacity: 0; visibility: hidden; pointer-events: none; }
    .loader { width: 60px; height: 60px; border: 4px solid var(--bg-tertiary); border-top-color: var(--accent-primary); border-radius: 50%; animation: spin 1s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
    @media (max-width: 1200px) { .main-container { grid-template-columns: 1fr; gap: 24px; } .sidebar { position: static; max-width: 100%; display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; } .footer-grid { grid-template-columns: 1fr; } .share-grid { grid-template-columns: repeat(4, 1fr); } }
    @media (max-width: 768px) { header { padding: 12px 4%; gap: 8px; } .logo { font-size: 20px; } .logo span { display: none; } .logo-icon { width: 38px; height: 38px; font-size: 18px; } .live-badge { padding: 4px 8px; font-size: 10px; } #file-name { margin: 0 8px; } #file-name marquee { font-size: 13px; } .theme-switcher { padding: 3px; gap: 3px; } .theme-btn { width: 32px; height: 32px; font-size: 14px; } .main-container { padding: 20px 4%; gap: 20px; grid-template-columns: 1fr; } .player-container { border-radius: var(--radius-lg); } .video-info { padding: 20px; } .video-title { font-size: 20px; } .video-header { gap: 12px; } .bookmark-btn { width: 40px; height: 40px; font-size: 16px; } .video-stats { gap: 8px; } .stat-item { font-size: 12px; padding: 6px 10px; } .action-buttons { grid-template-columns: repeat(2, 1fr); gap: 8px; } .action-btn { padding: 10px 12px; font-size: 12px; } .share-section { padding: 16px; } .share-grid { grid-template-columns: repeat(4, 1fr); gap: 8px; } .share-btn { padding: 10px 6px; font-size: 10px; } .share-btn i { font-size: 18px; } .sidebar { grid-template-columns: 1fr; gap: 16px; } .sidebar-card { padding: 16px; margin-bottom: 0; } .footer-grid { grid-template-columns: 1fr; gap: 28px; } .footer-copyright { font-size: 12px; padding: 10px 20px; } .footer-bottom-links { gap: 16px; } .footer-bottom-links a { font-size: 12px; padding: 6px 12px; } .toast { bottom: 16px; right: 16px; left: 16px; min-width: unset; max-width: unset; } }
    @media (max-width: 480px) { .logo-icon { width: 34px; height: 34px; font-size: 16px; } .live-badge { padding: 3px 6px; font-size: 9px; gap: 4px; } .live-badge::before { width: 5px; height: 5px; } .theme-btn { width: 30px; height: 30px; font-size: 13px; } .main-container { padding: 16px 3%; } .video-info { padding: 16px; } .video-title { font-size: 18px; } .video-stats { gap: 6px; } .stat-item { font-size: 11px; padding: 6px 8px; } .action-buttons { grid-template-columns: 1fr; } .action-btn { padding: 12px 16px; } .share-grid { grid-template-columns: repeat(2, 1fr); } .share-btn { padding: 12px 8px; } footer { padding: 40px 4% 24px; } .footer-logo { font-size: 22px; } .footer-description { font-size: 13px; } .footer-cta { padding: 12px 20px; font-size: 13px; } .footer-copyright { font-size: 11px; padding: 8px 16px; } .dev-credit { font-size: 10px; padding: 6px 12px; } .footer-bottom-links a { font-size: 11px; padding: 4px 8px; } }
  </style>
</head>

<body>
  <div class="loading-overlay hidden" id="loadingOverlay">
    <div class="loader"></div>
  </div>
  <div class="bg-decoration"></div>
  <div class="app-wrapper">
    <header>
      <div class="logo">
        <div class="logo-icon"><i class="fas fa-play"></i></div>
        <span>StreamX</span>
      </div>
      <div class="live-badge"><span>Live</span></div>
      <div id="file-name"><marquee direction="left">$filename</marquee></div>
      <div class="theme-switcher">
        <button class="theme-btn active" data-theme="default" title="Default Theme">🌈</button>
        <button class="theme-btn" data-theme="dark" title="Dark Theme">🌙</button>
        <button class="theme-btn" data-theme="light" title="Light Theme">☀️</button>
      </div>
    </header>
    <div class="main-container">
      <div class="player-wrapper">
        <div class="player-container">
          <$tag src="$src" class="player" controls></$tag>
          <div class="video-info">
            <div class="video-header">
              <h1 class="video-title">$filename</h1>
              <button class="bookmark-btn" id="bookmarkBtn" title="Add to Watchlist">
                <i class="far fa-bookmark bookmark-icon"></i>
                <i class="fas fa-check check-icon"></i>
              </button>
            </div>
            <div class="video-stats">
              <div class="stat-item"><i class="fas fa-eye"></i><span>Streaming</span></div>
              <div class="stat-item"><i class="fas fa-clock"></i><span>Just Now</span></div>
              <div class="stat-item quality"><i class="fas fa-crown"></i><span>HD</span></div>
              <div class="stat-item"><i class="fas fa-shield-alt"></i><span>Secure</span></div>
            </div>
            <div class="action-buttons">
              <button class="action-btn btn-primary" onclick="copyLink()"><i class="fas fa-link"></i><span>Copy Link</span></button>
              <button class="action-btn" onclick="downloadVideo()"><i class="fas fa-download"></i><span>Download</span></button>
              <button class="action-btn" onclick="shareVideo()"><i class="fas fa-share-nodes"></i><span>Share</span></button>
            </div>
            <div class="share-section">
              <div class="share-grid">
                <button class="share-btn btn-copy" onclick="copyLink()"><i class="fas fa-link"></i><span>Copy</span></button>
                <a class="share-btn btn-whatsapp" id="whatsapp-share" target="_blank"><i class="fab fa-whatsapp"></i><span>WhatsApp</span></a>
                <a class="share-btn btn-telegram" id="telegram-share" target="_blank"><i class="fab fa-telegram"></i><span>Telegram</span></a>
                <a class="share-btn btn-facebook" id="facebook-share" target="_blank"><i class="fab fa-facebook-f"></i><span>Facebook</span></a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <aside class="sidebar">
        <div class="sidebar-card">
          <div class="sidebar-title"><i class="fas fa-chart-line"></i>Stream Stats</div>
          <div class="stats-grid">
            <div class="stat-card"><div class="stat-label">Quality</div><div class="stat-value"><span class="quality-badge"><i class="fas fa-film"></i>1080p HD</span></div></div>
            <div class="stat-card"><div class="stat-label">Status</div><div class="stat-value" style="color: var(--success);">Active</div></div>
            <div class="stat-card"><div class="stat-label">Server</div><div class="stat-value" style="font-size: 16px;">Premium</div></div>
          </div>
        </div>
      </aside>
    </div>
    <footer>
      <div class="footer-content">
        <div class="footer-grid">
          <div class="footer-brand">
            <div class="footer-logo">StreamX</div>
            <p class="footer-description">Experience the next generation of video streaming. Fast, secure, and crystal clear.</p>
            <a href="tg://resolve?domain=filestream_iibot" class="footer-cta"><i class="fas fa-rocket"></i>Get Started Free</a>
            <div class="social-links">
              <a href="https://github.com/ShivamNox/FileStreamBot-Pro" class="social-link" title="GitHub" target="_blank"><i class="fab fa-github"></i></a>
              <a href="tg://resolve?domain=filestream_iibot" class="social-link" title="Telegram"><i class="fab fa-telegram"></i></a>
            </div>
          </div>
        </div>
        <div class="footer-bottom">
          <div class="copyright-container">
            <div class="footer-copyright">© 2024 Made with <i class="fas fa-heart"></i> by <a href="https://shivamnox.github.io" target="_blank">ShivamNox</a></div>
            <div class="dev-credit"><i class="fas fa-code"></i>Developer Edition</div>
          </div>
        </div>
      </div>
    </footer>
  </div>
  <div class="toast" id="toast"><i class="fas fa-check-circle"></i><div class="toast-content"><div class="toast-title">Success!</div><div class="toast-message">Link copied to clipboard</div></div></div>
  <script src="https://cdn.plyr.io/3.6.12/plyr.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const player = Plyr.setup(".player", { 
        controls: window.innerWidth <= 480 ? ["play-large","rewind","play","fast-forward","progress","current-time","duration","mute","volume","settings","fullscreen"] : 
                 window.innerWidth <= 768 ? ["play-large","rewind","play","fast-forward","progress","current-time","duration","mute","volume","settings","fullscreen"] :
                 ["play-large","rewind","play","fast-forward","progress","current-time","duration","mute","volume","captions","settings","pip","airplay","download","fullscreen"]
      });
      setupShareLinks();
    });
    const themeBtns = document.querySelectorAll('.theme-btn');
    themeBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        themeBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.body.classList.remove('dark', 'light');
        if (btn.dataset.theme !== 'default') document.body.classList.add(btn.dataset.theme);
        localStorage.setItem('streamx-theme', btn.dataset.theme);
      });
    });
    const savedTheme = localStorage.getItem('streamx-theme');
    if (savedTheme) themeBtns.forEach(btn => { if (btn.dataset.theme === savedTheme) btn.click(); });
    function setupShareLinks() {
      const currentUrl = encodeURIComponent(window.location.href);
      const title = encodeURIComponent(document.querySelector('.video-title').textContent);
      document.getElementById('whatsapp-share').href = `https://wa.me/?text=${title}%20${currentUrl}`;
      document.getElementById('telegram-share').href = `https://t.me/share/url?url=${currentUrl}&text=${title}`;
      document.getElementById('facebook-share').href = `https://www.facebook.com/sharer/sharer.php?u=${currentUrl}`;
    }
    function copyLink() {
      const url = window.location.href;
      navigator.clipboard.writeText(url).then(() => showToast('Success!', 'Link copied to clipboard')).catch(() => {
        const input = document.createElement('input'); input.value = url; document.body.appendChild(input); input.select(); document.execCommand('copy'); document.body.removeChild(input); showToast('Success!', 'Link copied to clipboard');
      });
    }
    function downloadVideo() { showToast('Info', 'Download will start shortly...'); const link = document.createElement('a'); link.href = document.querySelector('.player').src; link.download = '$filename'; link.click(); }
    function shareVideo() { if (navigator.share) { navigator.share({ title: document.querySelector('.video-title').textContent, url: window.location.href }).catch(() => {}); } else { copyLink(); } }
    function showToast(title, message, type = 'success') {
      const toast = document.getElementById('toast'); const toastTitle = toast.querySelector('.toast-title'); const toastMessage = toast.querySelector('.toast-message'); const toastIcon = toast.querySelector('i');
      toast.classList.remove('warning', 'error'); 
      if (type === 'warning') { toast.classList.add('warning'); toastIcon.className = 'fas fa-exclamation-circle'; } 
      else if (type === 'error') { toast.classList.add('error'); toastIcon.className = 'fas fa-times-circle'; } 
      else { toastIcon.className = 'fas fa-check-circle'; }
      toastTitle.textContent = title; toastMessage.textContent = message; toast.classList.add('show'); setTimeout(() => toast.classList.remove('show'), 3000);
    }
    document.addEventListener('keydown', (e) => { if ((e.ctrlKey || e.metaKey) && e.key === 'c') { e.preventDefault(); copyLink(); } });
  </script>
</body>
</html>