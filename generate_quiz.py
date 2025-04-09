import google.generativeai as genai

# Set up Google API key
genai.configure(api_key="AIzaSyCSDRc2sVPLuST5ybjkvObtekRW9kYVDOo")

# Function to generate quiz
def generate_quiz(topic):
    model = genai.GenerativeModel("gemini-pro")  # Correct model name
    prompt = f"Generate a multiple-choice quiz with 5 questions on {topic}, including correct answers."
    
    response = model.generate_content(prompt)
    
    if response and hasattr(response, 'text'):
        return response.text
    else:
        return "❌ Failed to generate quiz. Try again!"

# Example Usage
topic = "Python Programming"
quiz = generate_quiz(topic)
print(quiz)

