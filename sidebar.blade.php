  <!-- partial:partials/_sidebar.html -->
  <nav class="sidebar">
      <div class="sidebar-header">
          <a href="{{ route('index') }}" class="sidebar-brand">
              BB <span>Admin</span>
          </a>
          <div class="sidebar-toggler not-active">
              <span></span>
              <span></span>
              <span></span>
          </div>
      </div>
      <div class="sidebar-body">
          <ul class="nav">
              <li class="nav-item nav-category">Main</li>
              <li class="nav-item dashboard">
                  <a href="{{ route('admin.Dashboard') }}" class="nav-link">
                      <i class="link-icon" data-feather="box"></i>
                      <span class="link-title">Dashboard</span>
                  </a>
              </li>

              {{-- <li class="nav-item users">
                  <a href="{{ route('users') }}" class="nav-link">
                      <i class="link-icon" data-feather="box"></i>
                      <span class="link-title">Users</span>
                  </a>
              </li> --}}

              <li class="nav-item nav-category">Users</li>
              <li class="nav-item">
                  <a class="nav-link" data-bs-toggle="collapse" href="#users" role="button" aria-expanded="false"
                      aria-controls="users">
                      <i class="link-icon" data-feather="users"></i>
                      <span class="link-title">Users</span>
                      <i class="link-arrow" data-feather="chevron-down"></i>
                  </a>
                  <div class="collapse" id="users">
                      <ul class="nav sub-menu">
                          <li class="nav-item">
                              <a href="{{ route('users') }}" class="nav-link">Normal</a>
                          </li>
                          <li class="nav-item">
                              <a href="{{ route('promoters') }}" class="nav-link">Promoter</a>
                          </li>
                          <li class="nav-item">
                              <a href="{{ route('admins') }}" class="nav-link">Admin</a>
                          </li>
                          {{-- <li class="nav-item">
                              <a href="compose.html" class="nav-link">Sub Admin</a>
                          </li> --}}
                      </ul>
                  </div>
              </li>

              <li class="nav-item promoter-approval">
                  <a href="{{ route('promotersApproval') }}" class="nav-link">
                      <i class="link-icon" data-feather="box"></i>
                      <span class="link-title">Promoter Approval</span>
                  </a>
              </li>

              <li class="nav-item withdrawal-approval">
                  <a href="{{ route('withdrawalsApproval') }}" class="nav-link">
                      <i class="link-icon" data-feather="box"></i>
                      <span class="link-title">Withdrawal Approval</span>
                  </a>
              </li>

              <li class="nav-item payment-approval">
                  <a href="{{ route('paymentsApproval') }}" class="nav-link">
                      <i class="link-icon" data-feather="box"></i>
                      <span class="link-title">Payment Approval</span>
                  </a>
              </li>

              <li class="nav-item bank-list">
                  <a href="{{ route('bankDetails') }}" class="nav-link">
                      <i class="link-icon" data-feather="box"></i>
                      <span class="link-title">Bank List</span>
                  </a>
              </li>

              <li class="nav-item promoter-status">
                  <a href="{{ route('promoter.status') }}" class="nav-link">
                      <i class="link-icon" data-feather="box"></i>
                      <span class="link-title">Promoter Status</span>
                  </a>
              </li>


              <li class="nav-item coin-conversion">
                  <a href="{{ route('coin.conversion') }}" class="nav-link">
                      <i class="link-icon" data-feather="box"></i>
                      <span class="link-title">Coin Conversion</span>
                  </a>
              </li>


              <li class="nav-item promoter-percentage">
                  <a href="{{ route('promoter.percentage') }}" class="nav-link">
                      <i class="link-icon" data-feather="box"></i>
                      <span class="link-title">Promoter Percentage</span>
                  </a>
              </li>

              <li class="nav-item tournaments">
                  <a href="{{ route('admin.tournaments') }}" class="nav-link">
                      <i class="link-icon" data-feather="box"></i>
                      <span class="link-title">Tournaments</span>
                  </a>
              </li>

              <li class="nav-item matches">
                  <a href="{{ route('admin.matches') }}" class="nav-link">
                      <i class="link-icon" data-feather="box"></i>
                      <span class="link-title">Matches</span>
                  </a>
              </li>


              <li class="nav-item tds">
                  <a href="{{ route('TDS') }}" class="nav-link">
                      <i class="link-icon" data-feather="box"></i>
                      <span class="link-title">TDS</span>
                  </a>
              </li>

              <li class="nav-item gst">
                  <a href="{{ route('GST') }}" class="nav-link">
                      <i class="link-icon" data-feather="box"></i>
                      <span class="link-title">Deposit</span>
                  </a>
              </li>

              <li class="nav-item withdrawal">
                  <a href="{{ route('Withdrawal') }}" class="nav-link">
                      <i class="link-icon" data-feather="box"></i>
                      <span class="link-title">Withdrawal</span>
                  </a>
              </li>

              <li class="nav-item bet-value">
                  <a href="{{ route('min.bet.value') }}" class="nav-link">
                      <i class="link-icon" data-feather="box"></i>
                      <span class="link-title">Bet Value</span>
                  </a>
              </li>

              <li class="nav-item leader-board">
                  <a href="{{ route('admin.leader.board.index') }}" class="nav-link">
                      <i class="link-icon" data-feather="box"></i>
                      <span class="link-title">Leader Board</span>
                  </a>
              </li>

              <li class="nav-item player-notification">
                  <a href="{{ route('admin.player.notification') }}" class="nav-link">
                      <i class="link-icon" data-feather="box"></i>
                      <span class="link-title">Notification</span>
                  </a>
              </li>


              <li class="nav-item feedback">
                  <a href="{{ route('admin.feedback') }}" class="nav-link">
                      <i class="link-icon" data-feather="box"></i>
                      <span class="link-title">Feedback</span>
                  </a>
              </li>


              <li class="nav-item game-feedback">
                  <a href="{{ route('admin.game.feedback') }}" class="nav-link">
                      <i class="link-icon" data-feather="box"></i>
                      <span class="link-title">Game Feedback</span>
                  </a>
              </li>


          </ul>
      </div>
  </nav>
