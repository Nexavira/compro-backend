<div style="margin-top: auto; padding: 0 1rem 1rem 1rem; width: 100%;">
    <div class="footer-container" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background-color: rgba(255, 255, 255, 0.1); border-radius: 0.75rem; border: 1px solid rgba(255, 255, 255, 0.1); cursor: pointer; transition: all 0.3s ease;">
        <div style="width: 2.25rem; height: 2.25rem; border-radius: 9999px; background-color: #065f46; border: 1px solid rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden;">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&color=FFFFFF&background=064e3b" style="width: 100%; height: 100%; object-fit: cover;" alt="User Avatar" />
        </div>
        <div class="footer-text" style="display: flex; flex-direction: column; overflow: hidden;">
            <span style="font-size: 0.875rem; font-weight: 700; color: #ffffff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 1.25;">{{ auth()->user()->name ?? 'Diko Rifaldi Febrian' }}</span>
            <span style="font-size: 0.625rem; color: #a7f3d0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 0.125rem; letter-spacing: 0.05em; font-weight: 600; text-transform: uppercase;">SUPER ADMIN</span>
        </div>
    </div>
</div>
