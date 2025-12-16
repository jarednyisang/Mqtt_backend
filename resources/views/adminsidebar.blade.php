<aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="#" class="sidebar-brand">
                <i class="fas fa-globe"></i> Survey Hub
            </a>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="admindashboard" class="active">
                    <i class="fas fa-home"></i>
                    <span>Home</span>
                </a>
            </li>
            <li>
                <a href="systemusers">
                    <i class="fas fa-clipboard-list"></i>
                    <span>System users</span>
                </a>
            </li>
          
          
           
          
           
          <li>
    <a href="{{ url('/logout') }}" 
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
       style="margin-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem;">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
    </a>

    <form id="logout-form" action="{{ url('/logout') }}" method="GET" style="display: none;">
        @csrf
    </form>
</li>

        </ul>
    </aside>