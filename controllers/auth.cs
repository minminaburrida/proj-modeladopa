using Microsoft.AspNetCore.Mvc;
using Newtonsoft.Json;
using MySql.Data.MySqlClient;
using SqlConnection;

namespace Auth
{
    public class Usuario
    {
        public int Id { get; set; }
        public string Nombre { get; set; }
        public string Username { get; set; }
        public string Contraseña { get; set; }
    }

    public class LoginRequest
    {
        public string Username { get; set; }
        public string Password { get; set; }
    }

    // [ApiController]
    public class UsuarioController : ControllerBase
    {
        private static UsuarioController controller = new UsuarioController();
        public IActionResult Login([FromBody] LoginRequest request)
        {
            try
            {
                Usuario usuario = controller.Autenticar(request.Username, request.Password);

                if (usuario != null)
                {
                    // Aquí puedes generar y devolver un token JWT si la autenticación es exitosa
                    var token = controller.GenerarToken();

                    return Ok(new { Token = token });
                }
                else
                {
                    return BadRequest(new { Message = "Credenciales inválidas" });
                }
            }
            catch (Exception ex)
            {
                return StatusCode(500, new { Message = "Error en el servidor", Details = ex.Message });
            }
        }

        private string GenerarToken()
        {
            Random random = new Random();
            int numeroAleatorio = random.Next(10000, 100000);
            return numeroAleatorio.ToString();
        }




        public Usuario Autenticar(string username, string contraseña)
        {
            // Agrega la lógica para autenticar al usuario en la base de datos
            DbConnection.Instance.Open();
            MySqlCommand query = new MySqlCommand($"SELECT * FROM usuarios WHERE username = '{username}' AND contraseña = '{contraseña}'", DbConnection.Instance);
            MySqlDataReader reader = query.ExecuteReader();

            Usuario usuario = null;

            if (reader.Read())
            {
                usuario = new Usuario
                {
                    Id = reader.GetInt32("id"),
                    Nombre = reader.GetString("name"),
                    Username = reader.GetString("username"),
                    Contraseña = reader.GetString("contraseña")
                };
            }

            DbConnection.Instance.Close();

            return usuario;
        }

        public bool ValidarToken(string token)
        {
            // Lógica para validar el token (puedes usar bibliotecas como JWT)
            // Simplemente devolvemos true para este ejemplo
            return true;
        }

        public static async Task RefreshToken(HttpContext context)
        {
            // Obtener el token del encabezado de autorización
            string token = context.Request.Form["token"];

            // Verificar si el token existe
            if (!string.IsNullOrEmpty(token))
            {
                
                // Realizar la lógica para validar el token en tu base de datos
                var usuario = DbConnection.ExecuteAndGetResult("SELECT * FROM usuarios WHERE token = "+token);
                
                if (tokenIsValid)
                {
                    // Obtener la información de tiempo de la base de datos o cualquier otra fuente
                    DateTime lastLoginTime = GetLastLoginTime(token);

                    // Verificar si han pasado más de 5 minutos desde el último inicio de sesión
                    if (DateTime.Now - lastLoginTime <= TimeSpan.FromMinutes(5))
                    {
                        // Realizar acciones necesarias para actualizar el token o realizar otras operaciones
                        // Puedes renovar el token, actualizar la marca de tiempo, etc.

                        // Por ejemplo, renovar el token
                        string newToken = GenerateNewToken();
                        UpdateTokenInDatabase(token, newToken);

                        // Establecer el nuevo token en el encabezado de respuesta
                        context.Response.Headers["Authorization"] = newToken;
                    }
                    else
                    {
                        // El tiempo ha superado los 5 minutos, puedes tomar acciones adicionales si es necesario
                    }
                }
                else
                {
                    // El token no es válido, puedes manejar esto según tus necesidades
                }
            }
            else
            {
                // El token no está presente en el encabezado, puedes manejar esto según tus necesidades
            }
        }
        public static async Task Login(HttpContext context)
        {
            try
            {
                var form = context.Request.Form;

                Usuario usuario = controller.Autenticar(form["Username"], form["Password"]);
                Console.WriteLine("UWU");
                if (usuario != null)
                {
                    // Aquí puedes generar y devolver un token JWT si la autenticación es exitosa
                    string tokenObject = controller.GenerarToken();
                    DbConnection.Execute($"update usuarios set token = {tokenObject}, Last_Login = NOW() where id = {usuario.Id}");
                    context.Response.ContentType = "application/json";
                    await context.Response.WriteAsync(JsonConvert.SerializeObject(tokenObject));
                }
                else
                {
                    context.Response.StatusCode = 400;
                    context.Response.ContentType = "application/json";
                    await context.Response.WriteAsync(JsonConvert.SerializeObject(new { Message = "Credenciales inválidas" }));
                }

            }
            catch (Exception ex)
            {
                context.Response.StatusCode = 500;
                context.Response.ContentType = "application/json";
                await context.Response.WriteAsync(JsonConvert.SerializeObject(new
                {
                    Message = "Error en el servidor",
                    Details = ex.Message
                }));
            }
        }



    }
}
