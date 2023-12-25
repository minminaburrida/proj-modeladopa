using Microsoft.AspNetCore.Builder;
using Microsoft.AspNetCore.Hosting;
using Microsoft.AspNetCore.Http;
using Microsoft.Extensions.DependencyInjection;
using Microsoft.Extensions.Hosting;
using Newtonsoft.Json;
using System.Collections.Generic;
using MySql.Data.MySqlClient;
using Google.Protobuf.WellKnownTypes;

using SqlConnection;
namespace Habitaciones
{
    public class Habitacion
    {
        public int Id { get; set; }
        public required string Coordenadas { get; set; }
        public required string Estado { get; set; }
    }

    public class HabitacionController
    {
        private static HabitacionController controller = new HabitacionController();
        public List<Habitacion> GetFromDataBase(bool filtrado = false, string estado = "")
        {
            DbConnection.Instance.Open();
            // QuerySet para jalar las habitaciones
            MySqlCommand QuerySet = new MySqlCommand("SELECT * FROM habitaciones" +
                                    (filtrado ? $"where estado = '{estado}'" : ""), DbConnection.Instance);
            // Lectura de resultados
            using (MySqlDataReader lector = QuerySet.ExecuteReader())
            {
                // Crea una lista para almacenar los resultados
                List<Habitacion> habitaciones = new List<Habitacion>();

                // Itera sobre los resultados y agrega a la lista
                while (lector.Read())
                {
                    Habitacion habitacion = new Habitacion
                    {
                        Id = lector.GetInt32("id"),
                        Coordenadas = lector.GetString("cc"),
                        Estado = lector.GetString("estado")
                    };

                    habitaciones.Add(habitacion);
                }
                DbConnection.Instance.Close();
                return habitaciones;

                // Si no se solicita JSON, imprime los resultados en la consola

            }
        }
        public void editar(string id, string estado)
        {DbConnection.Execute($"update habitaciones set estado='{estado}' where id = {id}");}

        public static async Task Lista(HttpContext context)
        {
            var QP = context.Request.Query;
            bool filtro = !string.IsNullOrEmpty(QP["estado"]);

            List<Habitacion> habitaciones =
                HabitacionController.controller.GetFromDataBase(filtro, filtro ? QP["estado"] : "");
            string json = JsonConvert.SerializeObject(habitaciones);

            context.Response.ContentType = "application/json";
            await context.Response.WriteAsync(json);
        }
        public static async Task Edit(HttpContext context)
        {
            var QP = context.Request.Query;
            bool valid = !string.IsNullOrEmpty(QP["estado"]) && !string.IsNullOrEmpty(QP["id"]);

            string id = QP["id"];
            string estado = QP["estado"];

            string runtimeStatus = "OK";
            string runtimeMessage = "Datos editados correctamente";
            // Si id y estado
            if (valid)
            {
                try { HabitacionController.controller.editar(id, estado); }
                catch (Exception e)
                { runtimeStatus = "Error"; runtimeMessage = "Error en base de datos: " + e.ToString(); }
            }
            else
            { runtimeStatus = "Error"; runtimeMessage = "Datos no proporcionados"; }

            context.Response.ContentType = "application/json";
            await context.Response.WriteAsync(JsonConvert.SerializeObject(new
            {
                status = runtimeStatus,
                message = $"{runtimeMessage} en la habitacion {id}"
            }));

        }

    }
}