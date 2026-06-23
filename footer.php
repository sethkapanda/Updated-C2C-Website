<footer style="background: rgba(11, 15, 26, 0.6); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-top: 1px solid rgba(16, 185, 129, 0.15); padding: 40px 20px 20px; margin-top: 60px; color: #94a3b8; border-radius: 40px 40px 0 0;">

    <div style="max-width: 1280px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 30px; text-align: left;">

        <!-- Brand / About -->
        <div>
            <h3 style="color: #f1f5f9; font-size: 1.1rem; margin-bottom: 12px; letter-spacing: 0.5px;">Ubuntu<span style="background: linear-gradient(135deg, #10b981, #f59e0b); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Bazaar</span></h3>
            <p style="font-size: 0.9rem; line-height: 1.6; color: #94a3b8; max-width: 220px;">
                South Africa’s trusted C2C marketplace – connecting local sellers and buyers in your community.
            </p>
        </div>

        <!-- Quick Links -->
        <div>
            <h4 style="color: #e2e8f0; font-size: 0.9rem; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Quick Links</h4>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="margin-bottom: 8px;"><a href="index.php" style="color: #94a3b8; text-decoration: none; transition: color 0.2s;">Home</a></li>
                <li style="margin-bottom: 8px;"><a href="products.php" style="color: #94a3b8; text-decoration: none; transition: color 0.2s;">Products</a></li>
                <?php if(!isLoggedIn()): ?>
                    <li style="margin-bottom: 8px;"><a href="register.php" style="color: #94a3b8; text-decoration: none; transition: color 0.2s;">Register</a></li>
                    <li style="margin-bottom: 8px;"><a href="login.php" style="color: #94a3b8; text-decoration: none; transition: color 0.2s;">Login</a></li>
                <?php else: ?>
                    <li style="margin-bottom: 8px;"><a href="profile.php" style="color: #94a3b8; text-decoration: none; transition: color 0.2s;">My Profile</a></li>
                    <li style="margin-bottom: 8px;"><a href="cart.php" style="color: #94a3b8; text-decoration: none; transition: color 0.2s;">Cart</a></li>
                    <?php if(isSeller()): ?>
                        <li style="margin-bottom: 8px;"><a href="seller_dashboard.php" style="color: #94a3b8; text-decoration: none; transition: color 0.2s;">Dashboard</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Contact -->
        <div>
            <h4 style="color: #e2e8f0; font-size: 0.9rem; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Contact</h4>
            <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.9rem;">
                <li style="margin-bottom: 8px;">Mail: <a href="mailto:info@ubuntubazaar.co.za" style="color: #94a3b8; text-decoration: none; transition: color 0.2s;">info@ubuntubazaar.co.za</a></li>
                <li style="margin-bottom: 8px;">Tel: <a href="tel:+27123456789" style="color: #94a3b8; text-decoration: none; transition: color 0.2s;">+27 12 345 6789</a></li>
                <li style="margin-bottom: 8px;">Location: Johannesburg, South Africa</li>
            </ul>
        </div>

        <!-- Social & Payment -->
        <div>
            <h4 style="color: #e2e8f0; font-size: 0.9rem; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Follow Us</h4>
            <div style="display: flex; gap: 14px; font-size: 1.4rem; margin-bottom: 16px;">
                <a href="#" style="color: #94a3b8; text-decoration: none; transition: color 0.2s; font-size: 0.9rem;" title="Facebook">Facebook</a>
                <a href="#" style="color: #94a3b8; text-decoration: none; transition: color 0.2s; font-size: 0.9rem;" title="Twitter">Twitter</a>
                <a href="#" style="color: #94a3b8; text-decoration: none; transition: color 0.2s; font-size: 0.9rem;" title="Instagram">Instagram</a>
                <a href="#" style="color: #94a3b8; text-decoration: none; transition: color 0.2s; font-size: 0.9rem;" title="WhatsApp">WhatsApp</a>
            </div>
            <h4 style="color: #e2e8f0; font-size: 0.9rem; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Secure Payments</h4>
           
        </div>
    </div>

    <!-- Bottom bar -->
    <div style="text-align: center; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 20px; margin-top: 30px; font-size: 0.8rem; color: #64748b;">
        &copy; 2026 UbuntuBazaar – Supporting Local Enterprise Across South Africa.
        <span style="display: inline-block; margin: 0 8px;">•</span>
        Crafted with love in Mzansi.
    </div>
</footer>
