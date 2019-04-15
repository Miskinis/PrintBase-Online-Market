# PrintBase-Online-Market

College practice assignment. Create an online market with payment through bank transfer. Project includes:
1. Online Market Website
2. Online Bank Website
3. Database (market and bank combined to single file for simplicity)
4. XAMPP configuration for virtual server and SSL.

Check "Host configurations" for setup instructions.

Market refreshes data using ajax, this way reloading page in not required.
Market functionality is written in jQuery.
Market automatically populates itself with data taken from server database (if not connected, website will be empty).
Both market and bank have some level of protection (token check, SSL, validations).
