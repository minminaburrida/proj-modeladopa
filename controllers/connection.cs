using MySql.Data.MySqlClient;

namespace SqlConnection
{
    public class DbConnection
    {
        private static MySqlConnection _instance;
        private static readonly object _lock = new object();

        private DbConnection()
        {
            // Constructor privado para evitar instanciación directa
        }

        public static MySqlConnection Instance
        {
            get
            {
                lock (_lock)
                {
                    if (_instance == null)
                    {
                        _instance = new MySqlConnection("Server=localhost;Database=ibi;User Id=root;Password=;");
                    }
                    return _instance;
                }
            }
        }
        public static void Execute(string query)
        {
            _instance.Open();
            MySqlCommand QuerySet = new MySqlCommand(query, _instance);
            QuerySet.ExecuteNonQuery();
            _instance.Close();
        }

        public static string ExecuteAndGetResult(string query)
        {
            _instance.Open();

            try
            {
                MySqlCommand querySet = new MySqlCommand(query, _instance);
                object result = querySet.ExecuteScalar();

                // Convierte el resultado en crudo (texto) o cualquier otro formato que necesites
                string rawResult = (result != null) ? result.ToString() : null;

                return rawResult;
            }
            finally
            {
                _instance.Close();
            }
        }
    }
}
